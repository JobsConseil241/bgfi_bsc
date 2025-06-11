<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class WaAPIController extends Controller
{
    private string $apiToken;
    private string $instanceId;
    private string $baseUrl;
    private Client $client;
    
    public function __construct()
    {
        $this->apiToken = env('WAAPI_API_TOKEN');
        $this->instanceId = env('WAAPI_INSTANCE_ID');
        $this->baseUrl = 'https://waapi.app/api/v1';
        $this->client = new Client();
    }
    
    /**
     * Webhook principal - Reçoit les messages WhatsApp
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            $payload = $request->all();
            
            // Logger TOUT le payload pour debug
            Log::info('=== WEBHOOK REÇU ===', [
                'payload_complet' => $payload,
                'headers' => $request->headers->all()
            ]);
            
            if (!isset($payload['event']) || !isset($payload['data'])) {
                Log::warning('Format webhook invalide', $payload);
                return response()->json(['status' => 'error'], 400);
            }
            
            $eventType = $payload['event'];
            $eventData = $payload['data'];
            
            Log::info('Event détecté', [
                'type' => $eventType,
                'data' => $eventData
            ]);
            
            // Traiter TOUS les types de messages pour debug
            if (in_array($eventType, ['message', 'message_create'])) {
                $this->processMessage($eventData);
            } else {
                Log::info('Event ignoré', ['type' => $eventType]);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Erreur webhook: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }
    
    /**
     * Traite les messages entrants
     */
    private function processMessage(array $messageData): void
    {
        Log::info('=== PROCESS MESSAGE ===', [
            'messageData_complet' => $messageData
        ]);
        
        // Essayer différentes structures de données
        $data = null;
        $from = null;
        $message = '';
        $type = 'text';
        $messageId = null;
        
        // Structure 1: messageData['_data']
        if (isset($messageData['_data'])) {
            $data = $messageData['_data'];
            Log::info('Structure _data trouvée', $data);
        }
        // Structure 2: messageData['message']['_data']
        elseif (isset($messageData['message']['_data'])) {
            $data = $messageData['message']['_data'];
            Log::info('Structure message._data trouvée', $data);
        }
        // Structure 3: messageData direct
        else {
            $data = $messageData;
            Log::info('Structure directe utilisée', $data);
        }
        
        // Extraire l'ID du message pour éviter les doublons
        if (isset($data['id']['id'])) {
            $messageId = $data['id']['id'];
        } elseif (isset($data['messageId'])) {
            $messageId = $data['messageId'];
        } elseif (isset($data['key']['id'])) {
            $messageId = $data['key']['id'];
        }
        
        // Extraire FROM de différentes façons
        if (isset($data['from'])) {
            $from = $data['from'];
        } elseif (isset($data['id']['remote'])) {
            $from = $data['id']['remote'];
        } elseif (isset($data['key']['remoteJid'])) {
            $from = $data['key']['remoteJid'];
        }
        
        // Extraire MESSAGE de différentes façons
        if (isset($data['body'])) {
            $message = $data['body'];
        } elseif (isset($data['message']['conversation'])) {
            $message = $data['message']['conversation'];
        } elseif (isset($data['text'])) {
            $message = $data['text'];
        }
        
        // Extraire TYPE
        if (isset($data['type'])) {
            $type = $data['type'];
        } elseif (isset($data['messageType'])) {
            $type = $data['messageType'];
        }
        
        Log::info('Données extraites', [
            'messageId' => $messageId,
            'from' => $from,
            'message' => $message,
            'type' => $type,
            'fromMe' => $data['fromMe'] ?? 'non défini'
        ]);
        
        // VÉRIFICATION ANTI-DOUBLON
        if ($messageId) {
            $cacheKey = "processed_message_{$messageId}";
            
            if (Cache::has($cacheKey)) {
                Log::info('🚫 MESSAGE DÉJÀ TRAITÉ (doublon évité)', [
                    'messageId' => $messageId,
                    'from' => $from
                ]);
                return;
            }
            
            // Marquer comme traité pendant 5 minutes
            Cache::put($cacheKey, true, 300);
            Log::info('✅ Message marqué comme traité', ['messageId' => $messageId]);
        }
        
        // Vérifier si c'est notre message
        $isFromMe = $data['fromMe'] ?? false;
        if ($isFromMe === true) {
            Log::info('Message ignoré (fromMe = true)');
            return;
        }
        
        // Vérifications finales
        if (!$from) {
            Log::warning('FROM introuvable', $data);
            return;
        }
        
        if (empty(trim($message))) {
            Log::warning('MESSAGE vide', [
                'from' => $from,
                'message_brut' => $message
            ]);
            return;
        }
        
        // ANTI-DOUBLON PAR CONTENU (si pas d'ID)
        if (!$messageId) {
            $contentKey = "msg_content_" . md5($from . $message . time());
            $recentKey = "recent_" . md5($from . $message);
            
            if (Cache::has($recentKey)) {
                Log::info('🚫 MESSAGE IDENTIQUE RÉCENT (doublon évité)', [
                    'from' => $from,
                    'message' => substr($message, 0, 50)
                ]);
                return;
            }
            
            // Marquer pendant 30 secondes
            Cache::put($recentKey, true, 30);
        }
        
        Log::info('🚀 TRAITEMENT DU MESSAGE', [
            'messageId' => $messageId,
            'from' => $from,
            'message' => $message,
            'type' => $type
        ]);
        
        // Générer et envoyer la réponse
        $response = $this->generateResponse($message);
        if ($response) {
            $this->sendMessage($from, $response);
        } else {
            Log::warning('Aucune réponse générée', ['message' => $message]);
        }
    }
    
    /**
     * Génère une réponse simple basée sur le message
     */
    private function generateResponse(string $message): ?string
    {
        $message = strtolower(trim($message));
        
        // Salutations
        if ($this->contains($message, ['bonjour', 'salut', 'hello', 'hi', 'hey', 'bonsoir'])) {
            return "👋 Bonjour ! Je suis votre assistant virtuel.\n\n" .
                   "Je peux vous aider avec :\n" .
                   "• Nos produits et tarifs\n" .
                   "• Nos coordonnées\n" .
                   "• Passer une commande\n" .
                   "• Support technique\n\n" .
                   "Comment puis-je vous aider ? 😊";
        }
        
        // Au revoir
        if ($this->contains($message, ['au revoir', 'bye', 'à bientôt', 'ciao', 'tchao'])) {
            return "👋 Au revoir ! Passez une excellente journée ! À bientôt ! 🙂";
        }
        
        // Aide / Menu
        if ($this->contains($message, ['aide', 'help', 'menu', 'assistance'])) {
            return "🆘 **Menu d'aide**\n\n" .
                   "Voici ce que vous pouvez dire :\n\n" .
                   "• 'prix' ou 'tarifs' - Voir nos prix\n" .
                   "• 'contact' - Nos coordonnées\n" .
                   "• 'commander' - Passer commande\n" .
                   "• 'support' - Aide technique\n" .
                   "• 'horaires' - Nos heures d'ouverture\n\n" .
                   "Que souhaitez-vous savoir ? 🤔";
        }
        
        // Prix / Tarifs
        if ($this->contains($message, ['prix', 'tarif', 'coût', 'combien', 'budget'])) {
            return "💰 **Nos Tarifs**\n\n" .
                   "🌟 **Pack Starter** - 299€\n" .
                   "• Site vitrine 5 pages\n" .
                   "• Design responsive\n" .
                   "• Support 3 mois\n\n" .
                   "🚀 **Pack Pro** - 599€\n" .
                   "• Site e-commerce\n" .
                   "• Jusqu'à 50 produits\n" .
                   "• Paiement en ligne\n" .
                   "• Support 6 mois\n\n" .
                   "💎 **Pack Premium** - 999€\n" .
                   "• Site sur mesure\n" .
                   "• Fonctionnalités avancées\n" .
                   "• App mobile incluse\n" .
                   "• Support 1 an\n\n" .
                   "Quel pack vous intéresse ? 🤔";
        }
        
        // Produits / Services
        if ($this->contains($message, ['produit', 'service', 'offre', 'prestation'])) {
            return "🛍️ **Nos Services**\n\n" .
                   "• 🌐 Sites web professionnels\n" .
                   "• 📱 Applications mobiles\n" .
                   "• 🛒 Boutiques e-commerce\n" .
                   "• 🎨 Design & branding\n" .
                   "• 🚀 Marketing digital\n" .
                   "• 🔧 Maintenance & support\n\n" .
                   "Dites 'prix' pour voir nos tarifs ! 💰";
        }
        
        // Contact / Coordonnées
        if ($this->contains($message, ['contact', 'téléphone', 'email', 'adresse', 'coordonnées'])) {
            return "📞 **Nos Coordonnées**\n\n" .
                   "🏢 **Adresse**\n" .
                   "123 Rue de l'Innovation\n" .
                   "75001 Paris, France\n\n" .
                   "📱 **Téléphone**\n" .
                   "+33 1 23 45 67 89\n\n" .
                   "📧 **Email**\n" .
                   "contact@monentreprise.com\n\n" .
                   "🌐 **Site Web**\n" .
                   "www.monentreprise.com\n\n" .
                   "Nous sommes là pour vous ! 😊";
        }
        
        // Horaires
        if ($this->contains($message, ['horaire', 'ouvert', 'fermé', 'heure', 'ouvrir'])) {
            return "🕒 **Nos Horaires**\n\n" .
                   "📅 **Lundi - Vendredi**\n" .
                   "9h00 - 18h00\n\n" .
                   "📅 **Samedi**\n" .
                   "9h00 - 12h00\n\n" .
                   "📅 **Dimanche**\n" .
                   "Fermé\n\n" .
                   "Support d'urgence 24h/7j pour nos clients premium ! ⚡";
        }
        
        // Commande
        if ($this->contains($message, ['commander', 'commande', 'acheter', 'achat', 'réserver'])) {
            return "🛒 **Super ! Passons votre commande**\n\n" .
                   "Pour vous aider au mieux, dites-moi :\n\n" .
                   "• Quel type de projet ? (site web, app, etc.)\n" .
                   "• Votre budget approximatif ?\n" .
                   "• Dans quels délais ?\n\n" .
                   "Décrivez votre projet en quelques lignes ! ✍️\n\n" .
                   "Notre équipe vous contactera sous 24h ! 📞";
        }
        
        // Support / Problème
        if ($this->contains($message, ['support', 'problème', 'bug', 'erreur', 'panne', 'aide technique'])) {
            return "🔧 **Support Technique**\n\n" .
                   "Je vais vous aider à résoudre votre problème !\n\n" .
                   "Décrivez-moi :\n" .
                   "• Le problème exact\n" .
                   "• Sur quel service\n" .
                   "• Quel appareil (PC, mobile...)\n" .
                   "• Depuis quand\n\n" .
                   "Notre équipe technique vous répondra rapidement ! 🎯";
        }
        
        // Rendez-vous
        if ($this->contains($message, ['rendez-vous', 'rdv', 'rencontre', 'meeting', 'entretien'])) {
            return "📅 **Prise de Rendez-vous**\n\n" .
                   "Excellente idée ! Un RDV nous permettra de mieux vous conseiller.\n\n" .
                   "🗓️ **Créneaux disponibles :**\n" .
                   "• Mardi 14h-16h\n" .
                   "• Mercredi 10h-12h\n" .
                   "• Jeudi 15h-17h\n" .
                   "• Vendredi 9h-11h\n\n" .
                   "📞 **Format :**\n" .
                   "• Présentiel (bureau)\n" .
                   "• Visioconférence\n" .
                   "• Téléphone\n\n" .
                   "Quel format et créneau vous conviennent ? 🤔";
        }
        
        // Remerciements
        if ($this->contains($message, ['merci', 'thanks', 'thx', 'remercie'])) {
            return "😊 De rien ! C'est un plaisir de vous aider !\n\nN'hésitez pas si vous avez d'autres questions ! 🙂";
        }
        
        // Confirmation positive
        if ($this->contains($message, ['oui', 'yes', 'ok', 'd\'accord', 'parfait', 'génial'])) {
            return "👍 Parfait ! Notre équipe va traiter votre demande.\n\nVous serez contacté sous 24h ! 📞\n\nAutre chose ? 😊";
        }
        
        // Réponse par défaut
        return "🤖 Merci pour votre message !\n\n" .
               "Je ne suis pas sûr de comprendre exactement, mais je vais transmettre à notre équipe.\n\n" .
               "En attendant, tapez 'menu' pour voir ce que je peux faire ! 💡\n\n" .
               "Ou décrivez votre besoin simplement ! 😊";
    }
    
    /**
     * Vérifie si le message contient un des mots-clés
     */
    private function contains(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Envoie un message WhatsApp
     */
    private function sendMessage(string $to, string $message): void
    {
        try {
            Log::info('🔄 TENTATIVE ENVOI MESSAGE', [
                'to' => $to,
                'message' => substr($message, 0, 100) . '...',
                'api_token' => substr($this->apiToken, 0, 10) . '...',
                'instance_id' => $this->instanceId
            ]);
            
            $url = "{$this->baseUrl}/instances/{$this->instanceId}/client/action/send-message";
            
            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => "Bearer {$this->apiToken}",
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'chatId' => $to,
                    'message' => $message
                ]
            ]);
            
            $responseBody = $response->getBody()->getContents();
            $statusCode = $response->getStatusCode();
            
            Log::info('✅ MESSAGE ENVOYÉ AVEC SUCCÈS', [
                'to' => $to,
                'status_code' => $statusCode,
                'response' => $responseBody
            ]);
            
        } catch (RequestException $e) {
            $errorDetails = [
                'to' => $to,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ];
            
            if ($e->hasResponse()) {
                $errorDetails['response_body'] = $e->getResponse()->getBody()->getContents();
                $errorDetails['status_code'] = $e->getResponse()->getStatusCode();
            }
            
            Log::error('❌ ERREUR ENVOI MESSAGE', $errorDetails);
        } catch (\Exception $e) {
            Log::error('❌ ERREUR GÉNÉRALE ENVOI', [
                'to' => $to,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Test de l'API (optionnel)
     */
    public function test(): JsonResponse
    {
        try {
            $url = "{$this->baseUrl}/instances/{$this->instanceId}/status";
            
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => "Bearer {$this->apiToken}",
                ]
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Chatbot fonctionnel ! 🤖',
                'status' => json_decode($response->getBody()->getContents(), true)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}