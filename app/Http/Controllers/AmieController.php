<?php

namespace App\Http\Controllers;

use App\Mail\AlerteProspectMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Storage;

class AmieController extends Controller
{
    private Client $client;
    private TwilioClient $twilio;
    private string $apiToken;
    private string $instanceId;
    private string $twilioVerifyServiceSid;
    
    // Base de données des membres de la mutuelle
    private array $members = [
        'ESSE_2024001' => [
            'name' => 'Prime Clet ESSE',
            'code' => 'MCL2024',
            'phone' => '24176546985',
            'balance' => 450000,
            'currency' => 'FCFA',
            'adherent_number' => '2024001'
        ],
        'BOUNDAMAS_2024002' => [
            'name' => 'Jeff BOUNDAMAS',
            'code' => 'JBF2024',
            'phone' => '24177750737',
            'balance' => 875000,
            'currency' => 'FCFA',
            'adherent_number' => '2024002'
        ],
        'BIBANG_2024003' => [
            'name' => 'Eric BIBANG',
            'code' => 'EBI2024',
            'phone' => '241066492254',
            'balance' => 320000,
            'currency' => 'FCFA',
            'adherent_number' => '2024003'
        ],
        'EDOU_2024004' => [
            'name' => 'Nathanael EDOU',
            'code' => 'NED2024',
            'phone' => '24174228872',
            'balance' => 235000,
            'currency' => 'FCFA',
            'adherent_number' => '2024004'
        ],
        'NDJIBI_2024005' => [
            'name' => 'Hervé NDJIBI',
            'code' => 'HND2024',
            'phone' => '24177597658',
            'balance' => 505000,
            'currency' => 'FCFA',
            'adherent_number' => '2024005'
        ],
        'NDJEBI_2024006' => [
            'name' => 'Dimitri NDJEBI',
            'code' => 'DNJ2024',
            'phone' => '24174228877',
            'balance' => 1785000,
            'currency' => 'FCFA',
            'adherent_number' => '2024006'
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
     * Gérer les webhooks de messages entrants
     */
    public function handleMessages(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            Log::info('Webhook messages reçu', [
                'headers' => $request->headers->all(),
                'data' => $data
            ]);
            
            if (isset($data['messages']) && is_array($data['messages'])) {
                foreach ($data['messages'] as $message) {
                    $this->handleIncomingMessage($message);
                }
            }
            
            if (isset($data['statuses']) && is_array($data['statuses'])) {
                foreach ($data['statuses'] as $status) {
                    $this->handleMessageStatus($status);
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook traité avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Erreur traitement webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Gérer les messages entrants
     */
    private function handleIncomingMessage(array $message): void
    {
        $from = $message['from'] ?? '';
        $messageId = $message['id'] ?? '';
        $messageType = $message['type'] ?? 'unknown';
        
        Log::info('Message reçu', [
            'id' => $messageId,
            'from' => $from,
            'type' => $messageType
        ]);
        
        // Vérifier si ce message a déjà été traité (éviter les doublons)
        $cacheKey = "processed_msg_{$messageId}";
        if (Cache::has($cacheKey)) {
            Log::info('Message déjà traité, ignoré', ['id' => $messageId]);
            return;
        }
        
        // Marquer le message comme traité (5 minutes)
        Cache::put($cacheKey, true, 300);
        
        $this->markMessageAsRead($messageId);

        // Vérifier si c'est un nouveau contact (première interaction)
        $session = $this->getSession($from);
        $isFirstInteraction = !isset($session['has_started']) || !$session['has_started'];
        
        if ($isFirstInteraction) {
            // Marquer comme ayant démarré
            $this->updateSession($from, ['has_started' => true, 'state' => 'menu']);
            
            // Envoyer le message de bienvenue uniquement au premier contact
            $this->sendTextMessage($from, 
                "🏥 *Bienvenue à AMIE MUTUELLE*\n\n" .
                "Je suis votre assistant virtuel.\n" .
                "Je peux vous aider à consulter votre solde de manière sécurisée."
            );
            
            // Petit délai pour que les messages arrivent dans le bon ordre
            sleep(1);
            
            $this->sendMenuMessage($from);
            return;
        }

        switch ($messageType) {
            case 'text':
                $this->handleTextMessage($message);
                break;
                
            case 'reply':
                $this->handleInteractiveResponse($message);
                break;

            default:
                Log::warning('Type de message non géré', [
                    'type' => $messageType
                ]);
        }
    }

    /**
     * Gérer les messages texte
     */
    private function handleTextMessage(array $message): void
    {
        $text = trim($message['text']['body'] ?? '');
        $from = $message['from'] ?? '';
        
        Log::info('Message texte reçu', [
            'from' => $from,
            'text' => $text
        ]);

        $session = $this->getSession($from);
        $state = $session['state'] ?? 'menu';
        
        // COMMANDES GLOBALES - fonctionnent dans tous les états
        $textLower = strtolower($text);
        
        if (in_array($textLower, ['menu', '📋 menu', 'retour', 'annuler', 'stop'])) {
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
            return;
        }
        
        if (in_array($textLower, ['help', 'aide', '?'])) {
            $this->sendHelpMessage($from);
            return;
        }
        
        // Commandes de salutation - retour au menu
        if (in_array($textLower, ['bonjour', 'salut', 'hello', 'hi', 'bonsoir'])) {
            $this->sendTextMessage($from, 
                "👋 Bonjour !\n\n" .
                "Je suis l'assistant virtuel d'AMIE MUTUELLE.\n" .
                "Comment puis-je vous aider aujourd'hui ?"
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
            return;
        }

        // Gestion selon l'état de la session
        switch ($state) {
            case 'menu':
                $this->handleMenuState($from, $text);
                break;
                
            case 'waiting_name':
                $this->handleName($from, $text, $session);
                break;
                
            case 'waiting_code':
                $this->handleCode($from, $text, $session);
                break;
                
            case 'waiting_otp':
                $this->handleOtp($from, $text, $session);
                break;
                
            case 'balance_shown':
                // Après affichage du solde, tout message ramène au menu
                $this->sendMenuMessage($from);
                $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
                break;
                
            default:
                $this->sendMenuMessage($from);
                $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
        }
        
        if (isset($message['interactive'])) {
            $this->handleInteractiveResponse($message);
        }
    }

    /**
     * Gérer l'état du menu
     */
    private function handleMenuState(string $from, string $text): void
    {
        $textLower = strtolower(trim($text));
        
        // Commandes explicites
        if ($textLower === 'help' || $textLower === 'aide') {
            $this->sendHelpMessage($from);
            return;
        }
        
        if ($textLower === 'menu' || $textLower === '📋 menu') {
            $this->sendMenuMessage($from);
            return;
        }
        
        // Si l'utilisateur envoie un message non reconnu, on lui rappelle gentiment les options
        // MAIS on ne boucle pas - on envoie juste un message informatif
        if (!empty($text)) {
            $this->sendTextMessage($from,
                "Je n'ai pas compris votre demande.\n\n" .
                "💡 Tapez 'menu' pour voir les options disponibles"
            );
        }
    }

    /**
     * Gérer la réception du nom
     */
    private function handleName(string $from, string $name, array $session): void
    {
        $name = trim($name);
        
        if (strlen($name) < 3) {
            $this->sendTextMessage($from,
                "❌ Nom trop court.\n" .
                "📝 Veuillez saisir votre nom complet\n" .
                "💡 Exemple: Jean DUPONT\n\n" .
                "Tapez 'menu' pour annuler"
            );
            return;
        }
        
        // Sauvegarder le nom et demander le code
        $this->updateSession($from, [
            'state' => 'waiting_code',
            'name' => $name,
            'attempts' => 0
        ]);
        
        $this->sendTextMessage($from,
            "✅ Nom enregistré: *{$name}*\n\n" .
            "🔐 Veuillez maintenant saisir votre code adhérent\n" .
            "💡 Exemple: MCL2024\n" .
            "⏰ 3 tentatives maximum\n\n" .
            "Tapez 'menu' pour annuler"
        );
    }

    /**
     * Gérer la réception du code
     */
    private function handleCode(string $from, string $code, array $session): void
    {
        $code = strtoupper(trim($code));
        $name = $session['name'] ?? '';
        $attempts = ($session['attempts'] ?? 0) + 1;
        
        if (strlen($code) < 4) {
            $this->sendTextMessage($from,
                "❌ Code trop court.\n" .
                "📝 Veuillez saisir votre code complet\n\n" .
                "💡 Tapez 'menu' pour annuler"
            );
            return;
        }
        
        // Rechercher le membre avec nom ET code correspondants
        $memberFound = null;
        $memberKey = null;
        
        foreach ($this->members as $key => $member) {
            // Comparaison du code (exacte, insensible à la casse)
            $codeMatch = strtoupper($member['code']) === strtoupper($code);
            
            // Comparaison du nom (normalisée)
            $memberNameNorm = $this->normalizeString($member['name']);
            $inputNameNorm = $this->normalizeString($name);
            
            Log::info('Tentative de correspondance', [
                'member_name' => $member['name'],
                'member_name_norm' => $memberNameNorm,
                'input_name' => $name,
                'input_name_norm' => $inputNameNorm,
                'member_code' => $member['code'],
                'input_code' => $code,
                'name_match' => ($memberNameNorm === $inputNameNorm),
                'code_match' => $codeMatch
            ]);
            
            // Vérifier si le nom normalisé contient le nom saisi OU inversement
            $nameMatch = (
                $memberNameNorm === $inputNameNorm || 
                strpos($memberNameNorm, $inputNameNorm) !== false ||
                strpos($inputNameNorm, $memberNameNorm) !== false
            );
            
            if ($nameMatch && $codeMatch) {
                $memberFound = $member;
                $memberKey = $key;
                Log::info('Membre trouvé !', ['key' => $key, 'member' => $member]);
                break;
            }
        }
        
        if (!$memberFound) {
            if ($attempts >= 3) {
                $this->sendTextMessage($from,
                    "🚫 Trop de tentatives incorrectes.\n" .
                    "📞 Contactez le secrétariat de la mutuelle.\n\n" .
                    "Retour au menu..."
                );
                $this->sendMail($from, $name, $code);
                $this->sendMenuMessage($from);
                $this->updateSession($from, ['state' => 'menu']);
                return;
            }
            
            $remaining = 3 - $attempts;
            $this->sendTextMessage($from,
                "❌ Nom ou code incorrect.\n" .
                "🔄 Tentative {$attempts}/3 - {$remaining} essais restants\n\n" .
                "Veuillez vérifier:\n" .
                "👤 Nom: {$name}\n" .
                "🔐 Code: {$code}\n\n" .
                "💡 Le code doit correspondre exactement\n" .
                "💡 Tapez 'menu' pour recommencer"
            );
            
            $this->updateSession($from, array_merge($session, ['attempts' => $attempts]));
            return;
        }
        
        // Membre trouvé, envoyer l'OTP
        $otpSent = $this->sendTwilioOtp($memberFound['phone']);
        
        if (!$otpSent) {
            $this->sendTextMessage($from,
                "❌ Erreur lors de l'envoi du code de vérification.\n" .
                "📞 Contactez le secrétariat.\n\n" .
                "Retour au menu..."
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        $this->sendTextMessage($from,
            "✅ *Identification réussie*\n\n" .
            "👤 {$memberFound['name']}\n" .
            "📋 Adhérent N°: {$memberFound['adherent_number']}\n\n" .
            "📱 Code de vérification envoyé par SMS au:\n" .
            "    " . $this->maskPhone($memberFound['phone']) . "\n\n" .
            "🔐 Veuillez saisir le code reçu\n" .
            "⏰ 3 tentatives maximum"
        );
        
        $this->updateSession($from, [
            'state' => 'waiting_otp',
            'member_key' => $memberKey,
            'phone' => $memberFound['phone'],
            'otp_attempts' => 0
        ]);
    }

    /**
     * Gérer la vérification de l'OTP
     */
    private function handleOtp(string $from, string $code, array $session): void
    {
        $code = preg_replace('/[^0-9]/', '', $code);
        $attempts = ($session['otp_attempts'] ?? 0) + 1;
        $phone = $session['phone'] ?? '';
        $memberKey = $session['member_key'] ?? '';
        
        if (strlen($code) < 4) {
            $this->sendTextMessage($from, 
                "❌ Code trop court.\n" .
                "📝 Veuillez saisir le code reçu par SMS (4 à 6 chiffres)\n\n" .
                "💡 Tapez 'menu' pour annuler"
            );
            return;
        }
        
        Log::info('Vérification OTP', [
            'from' => $from,
            'code' => $code,
            'phone' => $phone,
            'attempts' => $attempts
        ]);
        
        $result = $this->verifyTwilioOtp($phone, $code);
        
        Log::info('Résultat vérification Twilio', [
            'result' => $result,
            'from' => $from
        ]);
        
        // IMPORTANT : Vérifier d'abord si le code est approuvé
        if ($result === 'approved') {
            if (!isset($this->members[$memberKey])) {
                Log::error('Membre non trouvé après OTP valide', ['member_key' => $memberKey]);
                $this->sendTextMessage($from, "❌ Erreur système. Contactez le secrétariat.");
                $this->sendMenuMessage($from);
                $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
                return;
            }
            
            $memberData = $this->members[$memberKey];
            Log::info('OTP approuvé, affichage du solde', ['member' => $memberData['name']]);
            
            $this->showBalance($from, $memberData, $session);
            $this->updateSession($from, [
                'state' => 'balance_shown',
                'otp_attempts' => 0,
                'attempts' => 0
            ]);
            
            // ARRÊT IMMÉDIAT de la fonction - ne pas continuer
            return;
        }
        
        // Si on arrive ici, c'est que l'OTP n'est PAS approuvé
        
        if ($attempts >= 3) {
            $this->sendTextMessage($from,
                "🚫 Accès bloqué après 3 tentatives.\n" .
                "📞 Contactez le secrétariat de la mutuelle.\n\n" .
                "Retour au menu..."
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
            return;
        }
        
        if ($result === 'expired') {
            $this->sendTextMessage($from,
                "⏰ Code expiré.\n" .
                "🔄 Veuillez recommencer la procédure.\n\n" .
                "Retour au menu..."
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu', 'attempts' => 0, 'otp_attempts' => 0]);
            return;
        }
        
        // Code incorrect
        $remaining = 3 - $attempts;
        $this->sendTextMessage($from,
            "❌ Code incorrect.\n" .
            "🔄 Tentative {$attempts}/3 - {$remaining} essais restants\n\n" .
            "💡 Tapez 'menu' pour annuler"
        );
        
        $this->updateSession($from, array_merge($session, ['otp_attempts' => $attempts]));
    }

    /**
     * Afficher le solde
     */
    private function showBalance(string $from, array $member, array $session): void
    {
        $balance = number_format($member['balance'], 0, ',', ' ');
        
        $menuText = "💰 *SOLDE COMPTE MUTUELLE*\n\n" .
            "👤 {$member['name']}\n" .
            "📋 Adhérent N°: {$member['adherent_number']}\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "💵 *{$balance} {$member['currency']}*\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "📅 " . date('d/m/Y à H:i') . "\n\n" .
            "✅ Merci de votre confiance !";
        
        $quickReplies = [
            ['id' => 'recepisse', 'title' => '📃 Reçu PDF'],
            ['id' => 'menu', 'title' => '📋 Menu']
        ];
        
        $this->sendInteractiveMessage($from, $menuText, $quickReplies);
        $this->generateReleve($from, $balance, $member, $session);
    }

    /**
     * Envoyer le menu principal
     */
    private function sendMenuMessage(string $to): void
    {
        $menuText = "🏥 *AMIE MUTUELLE - Menu Principal*\n\nChoisissez une option :";
        
        $quickReplies = [
            ['id' => 'solde', 'title' => '💰 Consulter Solde'],
            ['id' => 'info', 'title' => 'ℹ️ Informations'],
            ['id' => 'contact', 'title' => '📞 Contact']
        ];
        
        $this->sendInteractiveMessage($to, $menuText, $quickReplies);
    }

    /**
     * Envoyer un message d'aide
     */
    private function sendHelpMessage(string $to): void
    {
        $helpText = "🏥 *Bienvenue à AMIE MUTUELLE*\n\n" .
                   "📋 *Pour consulter votre solde:*\n" .
                   "1️⃣ Cliquez sur '💰 Consulter Solde'\n" .
                   "2️⃣ Saisissez votre nom complet\n" .
                   "3️⃣ Saisissez votre code adhérent\n" .
                   "4️⃣ Confirmez avec le code SMS\n\n" .
                   "🔄 *Commandes utiles:*\n" .
                   "• Tapez *menu* pour revenir au menu\n" .
                   "• Tapez *aide* pour revoir ce message\n" .
                   "• Tapez *annuler* pour stopper une opération\n\n" .
                   "💡 Vous pouvez taper 'menu' à tout moment !";
        
        $this->sendTextMessage($to, $helpText);
    }

    /**
     * Gérer les réponses interactives
     */
    private function handleInteractiveResponse(array $message): void
    {
        $from = $message['from'] ?? '';
        
        if (isset($message['reply']['buttons_reply'])) {
            $buttonId = $message['reply']['buttons_reply']['id'] ?? '';
            $buttonTitle = $message['reply']['buttons_reply']['title'] ?? '';
            
            Log::info('Réponse bouton reçue', [
                'from' => $from,
                'button_id' => $buttonId
            ]);
            
            $this->processButtonResponse($from, $buttonId, $buttonTitle);
        }
    }

    /**
     * Traiter les réponses aux boutons
     */
    private function processButtonResponse(string $from, string $buttonId, string $buttonTitle): void
    {
        $session = $this->getSession($from);
        
        switch ($buttonTitle) {
            case '💰 Consulter Solde':
                $this->sendTextMessage($from,
                    "🏥 *Consultation de Solde Sécurisée*\n\n" .
                    "📝 Veuillez saisir votre nom complet\n" .
                    "💡 Exemple: Jean DUPONT"
                );
                $this->updateSession($from, ['state' => 'waiting_name']);
                break;
            
            case '📋 Menu':
                $this->sendMenuMessage($from);
                $this->updateSession($from, ['state' => 'menu']);
                break;

            case 'ℹ️ Informations':
                $infoText = "ℹ️ *Informations Mutuelle*\n\n" .
                           "🏥 Mutuelle de Santé\n" .
                           "📞 Tél: +241 XX XX XX XX\n" .
                           "📧 Email: contact@mutuelle.ga\n" .
                           "🌐 Web: www.mutuelle.ga\n\n" .
                           "Horaires: Lun-Ven 8h-17h";
                $this->sendTextMessage($from, $infoText);
                break;
                
            case '📞 Contact':
                $contactText = "📞 *Nous Contacter*\n\n" .
                              "Appelez-nous:\n+241 XX XX XX XX\n\n" .
                              "Ou visitez notre site web";
                $quickReplies = [
                    ['id' => 'call', 'title' => '📞 Appeler', 'phone_number' => '+24101234567', 'type' => 'call'],
                    ['id' => 'web', 'title' => '🌐 Site Web', 'type' => 'url', 'url' => 'https://www.mutuelle.ga']
                ];
                $this->sendInteractiveMediaMessages($from, $contactText, $quickReplies);
                break;
                
            case '📃 Reçu PDF':
                if (isset($session['file_link']) && !empty($session['file_link'])) {
                    $this->sendPdfMessage($from, $session['file_link']);
                } else {
                    $this->sendTextMessage($from, "❌ Aucun reçu disponible");
                }
                break;
                
            default:
                $this->sendMenuMessage($from);
        }
    }

    /**
     * Normaliser une chaîne pour comparaison
     */
    private function normalizeString(string $str): string
    {
        // Convertir en majuscules
        $str = mb_strtoupper($str, 'UTF-8');
        
        // Retirer les accents et caractères spéciaux
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        
        // Retirer tout sauf lettres et chiffres
        $str = preg_replace('/[^A-Z0-9]/', '', $str);
        
        return trim($str);
    }

    /**
     * Envoyer un message interactif
     */
    public function sendInteractiveMessage(string $to, string $bodyText, array $quickReplies): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }
        
        $buttons = [];
        foreach ($quickReplies as $reply) {
            $buttons[] = [
                'type' => 'quick_reply',
                'id' => $reply['id'],
                'title' => $reply['title']
            ];
        }
        
        $payload = [
            'to' => $to,
            'header' => [
                'text' => "AMIE MUTUELLE"
            ],
            'body' => [
                'text' => $bodyText
            ],
            'type' => 'button',
            'action' => [
                'buttons' => $buttons
            ]
        ];
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/messages/interactive', $payload);
            
            if ($response->successful()) {
                Log::info('Message interactif envoyé', ['to' => $to]);
            } else {
                Log::error('Erreur envoi message interactif', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception envoi message', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Envoyer un message interactif avec media
     */
    public function sendInteractiveMediaMessages(string $to, string $bodyText, array $quickReplies): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) return;
        
        $buttons = [];
        foreach ($quickReplies as $reply) {
            $button = [
                'type' => $reply['type'] ?? 'quick_reply',
                'id' => $reply['id'],
                'title' => $reply['title']
            ];
            
            if (isset($reply['url'])) $button['url'] = $reply['url'];
            if (isset($reply['phone_number'])) $button['phone_number'] = $reply['phone_number'];
            
            $buttons[] = $button;
        }
        
        $payload = [
            'to' => $to,
            'header' => [
                'text' => "AMIE MUTUELLE"
            ],
            'body' => ['text' => $bodyText],
            'type' => 'button',
            'action' => ['buttons' => $buttons]
        ];
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/messages/interactive', $payload);
            
            if ($response->successful()) {
                Log::info('Message media envoyé', ['to' => $to]);
            }
        } catch (\Exception $e) {
            Log::error('Exception envoi media', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Envoyer un message texte simple
     */
    private function sendTextMessage(string $to, string $text): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) return;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/messages/text', [
                'to' => $to,
                'body' => $text
            ]);
            
            if ($response->successful()) {
                Log::info('Message texte envoyé', ['to' => $to]);
            }
        } catch (\Exception $e) {
            Log::error('Exception envoi texte', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Marquer un message comme lu
     */
    public function markMessageAsRead($messageId): bool
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) return false;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->put("{$baseUrl}/messages/{$messageId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Erreur marquage lu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un PDF par WhatsApp
     */
    private function sendPdfMessage(string $to, string $filename): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');

        if (!$token || !file_exists($filename)) return;

        try {
            $response = Http::withToken($token)
                ->attach('media', file_get_contents($filename), basename($filename))
                ->post($baseUrl . '/messages/document', [
                    'to' => $to,
                    'caption' => 'Voici votre reçu de consultation de solde',
                ]);

            if ($response->successful()) {
                Log::info('PDF envoyé avec succès', ['to' => $to]);
            }
        } catch (\Exception $e) {
            Log::error('Exception envoi PDF', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Générer un relevé PDF
     */
    public function generateReleve(string $to, string $solde, array $member, array $session): void
    {
        $data = [
            'nom' => $member['name'],
            'adherent' => $member['adherent_number'],
            'solde' => $solde . ' ' . $member['currency'],
            'date' => now()->format('d/m/Y'),
            'dateHeure' => now()->format('d/m/Y à H:i:s'),
        ];

        $pdf = PDF::loadView('pdf.mutuelle-compte', $data)
                  ->setPaper('A4', 'portrait');

        $fileName = 'releve-mutuelle-' . now()->format('Ymd-His') . '.pdf';
        Storage::disk('public')->put('releves/' . $fileName, $pdf->output());

        $filePath = storage_path('app/public/releves/' . $fileName);
        $this->updateSession($to, array_merge($session, ['file_link' => $filePath]));
    }

    /**
     * Envoyer un OTP via Twilio
     */
    private function sendTwilioOtp(string $phone): bool
    {
        try {
            $formattedPhone = $this->formatPhone($phone);

            $verification = $this->twilio->verify->v2
                ->services($this->twilioVerifyServiceSid)
                ->verifications
                ->create($formattedPhone, 'sms');
            
            Log::info('OTP Twilio envoyé', ['phone' => $formattedPhone]);
            return $verification->status === 'pending';
            
        } catch (TwilioException $e) {
            Log::error('Erreur Twilio OTP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier un OTP Twilio
     */
    private function verifyTwilioOtp(string $phone, string $code): string
    {
        try {
            $formattedPhone = $this->formatPhone($phone);

            $check = $this->twilio->verify->v2
                ->services($this->twilioVerifyServiceSid)
                ->verificationChecks
                ->create(['to' => $formattedPhone, 'code' => $code]);
            
            Log::info('Vérification Twilio', ['status' => $check->status]);
            return $check->status;
            
        } catch (TwilioException $e) {
            Log::error('Erreur vérification: ' . $e->getMessage());
            
            if (str_contains(strtolower($e->getMessage()), 'expired')) return 'expired';
            return 'pending';
        }
    }

    /**
     * Formater un numéro de téléphone
     */
    private function formatPhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($clean, '241')) {
            $clean = '0' . substr($clean, 3);
        }

        if (str_starts_with($clean, '0')) {
            return '+241' . substr($clean, 1);
        }
        
        if (!str_starts_with($clean, '+')) {
            return str_starts_with($clean, '241') ? '+' . $clean : '+241' . $clean;
        }

        return $clean;
    }

    /**
     * Masquer un numéro de téléphone
     */
    private function maskPhone(string $phone): string
    {
        return strlen($phone) >= 8 
            ? substr($phone, 0, 4) . '***' . substr($phone, -2) 
            : $phone;
    }

    /**
     * Récupérer la session
     */
    private function getSession(string $from): array
    {
        return Cache::get("mutuelle_session_{$from}", [
            'state' => 'menu',
            'has_started' => false,
            'name' => '',
            'attempts' => 0,
            'otp_attempts' => 0,
            'file_link' => ''
        ]);
    }

    /**
     * Mettre à jour la session
     */
    private function updateSession(string $from, array $data): void
    {
        $session = array_merge($this->getSession($from), $data);
        Cache::put("mutuelle_session_{$from}", $session, 1800); // 30 minutes
    }

    /**
     * Envoyer un email d'alerte
     */
    private function sendMail(string $numero, string $name = '', string $code = ''): void
    {
        try {
            $emailEquipe = 'secretariat@mutuelle.ga';
            
            Mail::to($emailEquipe)->send(
                new AlerteProspectMail($numero, "Tentatives multiples - {$name} / {$code}")
            );
        } catch (\Exception $e) {
            Log::error('Erreur envoi mail: ' . $e->getMessage());
        }
    }

    /**
     * Gérer les statuts de messages
     */
    private function handleMessageStatus(array $status): void
    {
        Log::info('Statut message', [
            'id' => $status['id'] ?? '',
            'status' => $status['status'] ?? ''
        ]);
    }
}