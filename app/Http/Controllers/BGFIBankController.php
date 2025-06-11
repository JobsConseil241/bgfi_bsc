<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;

class BGFIBankController extends Controller
{
    private Client $client;
    private TwilioClient $twilio;
    private string $apiToken;
    private string $instanceId;
    private string $twilioVerifyServiceSid;
    
    // Base de données des comptes BGFI
    private array $accounts = [
        '1000123456' => [
            'name' => 'Prime Clet',
            'phone' => '24176546985',
            'balance' => 2450000,
            'currency' => 'XAF'
        ],
        '1000234567' => [
            'name' => 'Jeff Boundamas', 
            'phone' => '24177750737',
            'balance' => 8750000,
            'currency' => 'XAF'
        ],
        '1000345678' => [
            'name' => 'Paul OBAMA',
            'phone' => '24107345678', 
            'balance' => 1200000,
            'currency' => 'XAF'
        ]
    ];
    
    public function __construct()
    {
        $this->apiToken = env('WAAPI_API_TOKEN');
        $this->instanceId = env('WAAPI_INSTANCE_ID');
        $this->twilioVerifyServiceSid = env('TWILIO_VERIFY_SID');
        
        $this->client = new Client();
        $this->twilio = new TwilioClient(env('TWILIO_SID'), env('TWILIO_TOKEN'));
    }
    
    /**
     * Webhook principal
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            $payload = $request->all();
            
            if (!isset($payload['event'], $payload['data'])) {
                return response()->json(['status' => 'error'], 400);
            }
            
            if ($payload['event'] === 'message') {
                $this->processMessage($payload['data']);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Erreur webhook BGFI: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
    
    /**
     * Traite les messages entrants
     */
    private function processMessage(array $messageData): void
    {
        $data = $messageData['_data'] ?? $messageData['message']['_data'] ?? $messageData;
        
        // Anti-doublon
        $messageId = $data['id']['id'] ?? null;
        if ($messageId && Cache::has("bgfi_{$messageId}")) return;
        if ($messageId) Cache::put("bgfi_{$messageId}", true, 300);
        
        // Ignorer nos messages
        if (isset($data['fromMe']) && $data['fromMe'] === true) return;
        
        $from = $data['from'] ?? $data['id']['remote'] ?? null;
        $message = trim($data['body'] ?? $data['message']['conversation'] ?? '');
        
        if (!$from || empty($message)) return;
        
        $this->handleConversation($from, $message);
    }
    
    /**
     * Gère la conversation BGFI
     */
    private function handleConversation(string $from, string $message): void
    {
        $session = $this->getSession($from);
        $input = strtolower(trim($message));
        
        switch ($session['state']) {
            case 'menu':
                $this->handleMenu($from, $input, $session);
                break;
                
            case 'waiting_account':
                $this->handleAccount($from, $message, $session);
                break;
                
            case 'waiting_otp':
                $this->handleOtp($from, $message, $session);
                break;
                
            default:
                $this->showWelcome($from);
                $this->updateSession($from, ['state' => 'menu']);
        }
    }
    
    
    private function handleMenu(string $from, string $input, array $session): void
    {
        if (in_array($input, ['1', 'menu'])) {
            $this->showMenu($from);
            return;
        }
        
        if (in_array($input, ['2', 'solde'])) {
            $this->requestAccount($from);
            $this->updateSession($from, ['state' => 'waiting_account', 'attempts' => 0]);
            return;
        }
        
        if (in_array($input, ['3', 'quitter', 'au revoir'])) {
            $this->showGoodbye($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        $this->sendMessage($from, 
            "❌ Option non reconnue.\n\n" .
            "Choisissez :\n1️⃣ Menu\n2️⃣ Solde\n3️⃣ Quitter"
        );
    }
    
    
    private function handleAccount(string $from, string $account, array $session): void
    {
        $account = preg_replace('/[^0-9]/', '', $account);
        
        if (strlen($account) !== 10) {
            $this->sendMessage($from,
                "❌ Numéro invalide. Saisissez 10 chiffres.\n📝 Exemple: 1000123456"
            );
            return;
        }
        
        if (!isset($this->accounts[$account])) {
            $attempts = ($session['attempts'] ?? 0) + 1;
            
            if ($attempts >= 3) {
                $this->sendMessage($from,
                    "🚫 Trop de tentatives. Contactez le *880*.\n\nRetour au menu..."
                );
                $this->showMenu($from);
                $this->updateSession($from, ['state' => 'menu']);
                return;
            }
            
            $this->sendMessage($from, "❌ Compte introuvable. Tentative {$attempts}/3");
            $this->updateSession($from, ['state' => 'waiting_account', 'attempts' => $attempts]);
            return;
        }
        
        
        $accountData = $this->accounts[$account];
        $otpSent = $this->sendTwilioOtp($accountData['phone']);
        
        if (!$otpSent) {
            $this->sendMessage($from,
                "❌ Erreur envoi OTP. Contactez le *880*.\n\nRetour au menu..."
            );
            $this->showMenu($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        $this->sendMessage($from,
            "✅ Compte: {$accountData['name']}\n" .
            "📱 SMS envoyé au " . $this->maskPhone($accountData['phone']) . "\n\n" .
            "🔐 Saisissez le code reçu:\n⏰ 3 tentatives max"
        );
        
        $this->updateSession($from, [
            'state' => 'waiting_otp',
            'account' => $account,
            'phone' => $accountData['phone'],
            'otp_attempts' => 0
        ]);
    }
    

    private function handleOtp(string $from, string $code, array $session): void
    {
        $code = preg_replace('/[^0-9]/', '', $code);
        $attempts = ($session['otp_attempts'] ?? 0) + 1;
        $phone = $session['phone'] ?? '';
        $account = $session['account'] ?? '';
        
        if (strlen($code) < 4) {
            $this->sendMessage($from, "❌ Code trop court. Saisissez le code complet:");
            return;
        }
        
        $result = $this->verifyTwilioOtp($phone, $code);
        
        if ($result === 'approved') {
            $accountData = $this->accounts[$account];
            $this->showBalance($from, $accountData, $account);
            $this->showMenu($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        if ($attempts >= 3) {
            $this->sendMessage($from,
                "🚫 Accès bloqué après 3 tentatives.\n📞 Contactez le *880*\n\nRetour au menu..."
            );
            $this->showMenu($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        if ($result === 'expired') {
            $this->sendMessage($from,
                "⏰ Code expiré. Tapez '2' pour relancer.\n\nRetour au menu..."
            );
            $this->showMenu($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        $remaining = 3 - $attempts;
        $this->sendMessage($from,
            "❌ Code incorrect. Tentative {$attempts}/3\n🔄 {$remaining} essais restants"
        );
        
        $this->updateSession($from, array_merge($session, ['otp_attempts' => $attempts]));
    }
    

    private function showWelcome(string $from): void
    {
        $this->sendMessage($from,
            "🏦 **BGFI Bank**\n🌟 *Ensemble, Bâtissons Votre Avenir*\n\n" .
            "Bonjour ! Je suis votre assistant bancaire.\n\nComment puis-je vous aider ?"
        );
        $this->showMenu($from);
    }
    
    
    private function showMenu(string $from): void
    {
        $this->sendMessage($from,
            "📋 **MENU BGFI**\n\n" .
            "1️⃣ Menu - Afficher options\n" .
            "2️⃣ Solde - Consulter solde\n" .
            "3️⃣ Quitter - Terminer\n\n" .
            "💬 Tapez le numéro\n📞 Urgence: *880*"
        );
    }
    
    
    private function requestAccount(string $from): void
    {
        $this->sendMessage($from,
            "🔐 **Consultation Solde**\n\n" .
            "📝 Numéro de compte (10 chiffres):\n💡 Ex: 1000123456"
        );
    }
    
    
    private function showBalance(string $from, array $account, string $accountNumber): void
    {
        $balance = number_format($account['balance'], 0, ',', ' ');
        
        $this->sendMessage($from,
            "💰 **SOLDE COMPTE**\n\n" .
            "👤 {$account['name']}\n" .
            "🏦 " . $this->maskAccount($accountNumber) . "\n\n" .
            "💵 **{$balance} {$account['currency']}**\n\n" .
            "📅 " . date('d/m/Y à H:i') . "\n" .
            "✅ BGFI Bank vous remercie !"
        );
    }
    
    
    private function showGoodbye(string $from): void
    {
        $this->sendMessage($from,
            "👋 **Au revoir !**\n\n" .
            "Merci d'avoir utilisé BGFI Bank.\n\n" .
            "🏦 **BGFI Bank**\n" .
            "🌟 *\"Ensemble, Bâtissons Votre Avenir\"*\n\n" .
            "📞 Urgence: *880*\nÀ bientôt ! 😊"
        );
    }
    
    
    private function sendTwilioOtp(string $phone): bool
    {
        try {
            $formattedPhone = $this->formatPhone($phone);
            
            $verification = $this->twilio->verify->v2
                ->services($this->twilioVerifyServiceSid)
                ->verifications
                ->create($formattedPhone, 'sms');
            
            Log::info('OTP Twilio envoyé', ['phone' => $formattedPhone, 'status' => $verification->status]);
            
            return $verification->status === 'pending';
            
        } catch (TwilioException $e) {
            Log::error('Erreur Twilio OTP: ' . $e->getMessage());
            return false;
        }
    }
    

    private function verifyTwilioOtp(string $phone, string $code): string
    {
        try {
            $formattedPhone = $this->formatPhone($phone);
            
            $check = $this->twilio->verify->v2
                ->services($this->twilioVerifyServiceSid)
                ->verificationChecks
                ->create(['to' => $formattedPhone, 'code' => $code]);
            
            Log::info('Vérification Twilio', ['phone' => $formattedPhone, 'status' => $check->status]);
            
            return $check->status; 
            
        } catch (TwilioException $e) {
            Log::error('Erreur vérification Twilio: ' . $e->getMessage());
            
            if (str_contains(strtolower($e->getMessage()), 'expired')) return 'expired';
            if (str_contains(strtolower($e->getMessage()), 'invalid')) return 'pending';
            
            return 'error';
        }
    }
    
    
    private function formatPhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($clean, '0')) {
            return '+241' . substr($clean, 1);
        }
        
        if (!str_starts_with($clean, '+')) {
            return str_starts_with($clean, '241') ? '+' . $clean : '+241' . $clean;
        }
        
        return $clean;
    }
    
    
    private function maskPhone(string $phone): string
    {
        return strlen($phone) >= 8 ? substr($phone, 0, 4) . '***' . substr($phone, -2) : $phone;
    }
    
    
    private function maskAccount(string $account): string
    {
        return strlen($account) >= 6 ? substr($account, 0, 4) . '***' . substr($account, -3) : $account;
    }
    
    
    private function getSession(string $from): array
    {
        return Cache::get("bgfi_session_{$from}", [
            'state' => 'menu',
            'attempts' => 0,
            'otp_attempts' => 0
        ]);
    }
    
   
    private function updateSession(string $from, array $data): void
    {
        $session = array_merge($this->getSession($from), $data);
        Cache::put("bgfi_session_{$from}", $session, 1800); // 30 min
    }
    
    
    private function sendMessage(string $to, string $message): void
    {
        try {
            $this->client->request('POST', "https://waapi.app/api/v1/instances/{$this->instanceId}/client/action/send-message", [
                'headers' => [
                    'authorization' => "Bearer {$this->apiToken}",
                    'content-type' => 'application/json',
                ],
                'json' => ['chatId' => $to, 'message' => $message]
            ]);
            
        } catch (RequestException $e) {
            Log::error('Erreur envoi WhatsApp: ' . $e->getMessage());
        }
    }
    
    
    public function test(): JsonResponse
    {
        try {
            // Test Twilio Verify
            $verifyStatus = 'Non configuré';
            if ($this->twilioVerifyServiceSid) {
                try {
                    $service = $this->twilio->verify->v2->services($this->twilioVerifyServiceSid)->fetch();
                    $verifyStatus = 'Actif - ' . $service->friendlyName;
                } catch (\Exception $e) {
                    $verifyStatus = 'Erreur: ' . $e->getMessage();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'BGFI Bank Chatbot opérationnel ! 🏦',
                'services' => [
                    'whatsapp' => 'Actif',
                    'twilio_verify' => $verifyStatus,
                    'security' => '3 tentatives max'
                ],
                'comptes_test' => [
                    '1000123456' => 'Jean MBONGO - 2,450,000 XAF',
                    '1000234567' => 'Marie EYENGA - 875,000 XAF', 
                    '1000345678' => 'Paul OBAMA - 1,200,000 XAF'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}