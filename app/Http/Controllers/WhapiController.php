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
use Ramsey\Uuid\Type\Integer;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Storage;

class WhapiController extends Controller
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
            'name' => 'Eric BIBANG',
            'phone' => '241066492254', 
            'balance' => 1200000,
            'currency' => 'XAF'
        ],
        '1000456789' => [
            'name' => 'Nathanael EDOU',
            'phone' => '24174228872', 
            'balance' => 8235000,
            'currency' => 'XAF'
        ],
        '1000567890' => [
            'name' => 'Hervé NDJIBI',
            'phone' => '24177597658', 
            'balance' => 4505000,
            'currency' => 'XAF'
        ],
        '1000678901' => [
            'name' => 'Dimitri NDJEBI',
            'phone' => '24174228877', 
            'balance' => 253785000,
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
     * Gérer les webhooks de messages entrants de Whapi.cloud
     */
    public function handleMessages(Request $request): JsonResponse
    {
        try {
            // Récupérer toutes les données du webhook
            $data = $request->all();
            
            // Logger les données reçues pour debug
            Log::info('Webhook Whapi messages reçu', [
                'headers' => $request->headers->all(),
                'data' => $data
            ]);
            
            // Traitement spécifique aux messages
            if (isset($data['messages']) && is_array($data['messages'])) {
                foreach ($data['messages'] as $message) {
                    $this->handleIncomingMessage($message);
                }
            }
            
            // Traitement des statuts de messages
            if (isset($data['statuses']) && is_array($data['statuses'])) {
                foreach ($data['statuses'] as $status) {
                    $this->handleMessageStatus($status);
                }
            }
            
            // Réponse de succès obligatoire pour Whapi
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook messages traité avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            // Logger l'erreur
            Log::error('Erreur traitement webhook Whapi messages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            // Retourner une erreur 500
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur interne du serveur'
            ], 500);
        }
    }
    
    /**
     * Gérer les webhooks de chats de Whapi.cloud
     */
    public function handleChats(Request $request): JsonResponse
    {
        try {
            // Récupérer toutes les données du webhook
            $data = $request->all();
            
            // Logger les données reçues pour debug
            Log::info('Webhook Whapi chats reçu', [
                'headers' => $request->headers->all(),
                'data' => $data
            ]);
            
            // Traitement spécifique aux chats
            if (isset($data['chats']) && is_array($data['chats'])) {
                foreach ($data['chats'] as $chat) {
                    $this->handleChatEvent($chat);
                }
            }
            
            // Traitement des événements de groupe
            if (isset($data['groups']) && is_array($data['groups'])) {
                foreach ($data['groups'] as $group) {
                    $this->handleGroupEvent($group);
                }
            }
            
            // Traitement des événements de contact
            if (isset($data['contacts']) && is_array($data['contacts'])) {
                foreach ($data['contacts'] as $contact) {
                    $this->handleContactEvent($contact);
                }
            }
            
            // Réponse de succès obligatoire pour Whapi
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook chats traité avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            // Logger l'erreur
            Log::error('Erreur traitement webhook Whapi chats', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            // Retourner une erreur 500
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Gérer les webhooks entrants de Whapi.cloud (générique)
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            // Récupérer toutes les données du webhook
            $data = $request->all();
            
            // Logger les données reçues pour debug
            Log::info('Webhook Whapi reçu', [
                'headers' => $request->headers->all(),
                'data' => $data
            ]);
            
            // Traitement principal des données
            $this->processWebhookData($data);
            
            // Réponse de succès obligatoire pour Whapi
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook traité avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            // Logger l'erreur
            Log::error('Erreur traitement webhook Whapi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            // Retourner une erreur 500
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur interne du serveur'
            ], 500);
        }
    }
    
    /**
     * Traiter les données du webhook selon leur type
     */
    private function processWebhookData(array $data): void
    {
        // Traitement des messages reçus
        if (isset($data['messages']) && is_array($data['messages'])) {
            foreach ($data['messages'] as $message) {
                $this->handleIncomingMessage($message);
            }
        }
        
        // Traitement des statuts de messages (lu, livré, etc.)
        if (isset($data['statuses']) && is_array($data['statuses'])) {
            foreach ($data['statuses'] as $status) {
                $this->handleMessageStatus($status);
            }
        }
        
        // Traitement des événements de groupe
        if (isset($data['groups']) && is_array($data['groups'])) {
            foreach ($data['groups'] as $group) {
                $this->handleGroupEvent($group);
            }
        }
        
        // Traitement des événements de contact
        if (isset($data['contacts']) && is_array($data['contacts'])) {
            foreach ($data['contacts'] as $contact) {
                $this->handleContactEvent($contact);
            }
        }
    }
    
    /**
     * Gérer les messages entrants
     */
    private function handleIncomingMessage(array $message): void
    {
        $from = $message['from'] ?? '';
        $timestamp = $message['timestamp'] ?? time();
        $messageId = $message['id'] ?? '';
        
        Log::info('Message reçu', [
            'id' => $messageId,
            'from' => $from,
            'timestamp' => date('Y-m-d H:i:s', $timestamp)
        ]);
        
        // Traitement selon le type de message
        $messageType = $message['type'] ?? 'unknown';
        

        $this->markMessageAsRead($messageId);

        switch ($messageType) {
            case 'text':
                $this->handleTextMessage($message);
                break;
                
            case 'image':
                $this->handleImageMessage($message);
                break;
                
            case 'document':
                $this->handleDocumentMessage($message);
                break;
                
            case 'audio':
                $this->handleAudioMessage($message);
                break;
                
            case 'video':
                $this->handleVideoMessage($message);
                break;
                
            case 'location':
                $this->handleLocationMessage($message);
                break;
                
            case 'contact':
                $this->handleContactMessage($message);
                break;
                
            case 'reply' : 
                $this->handleInteractiveResponse($message);
                break;

            default:
                Log::warning('Type de message non géré', [
                    'type' => $messageType,
                    'message' => $message
                ]);
        }
        
        // Exemple de réponse automatique
        // $this->sendAutoReply($from, $message);
    }
    
    /**
     * Summary of supprimerEmojis
     * @param mixed $texte
     * @return array|string|null
     */
    function supprimerEmojis($texte) {
        // Supprime les emojis courants (emoticons, symboles, pictogrammes, etc.)
        return preg_replace(
            '/[\x{1F300}-\x{1F5FF}]|[\x{1F600}-\x{1F64F}]|[\x{1F680}-\x{1F6FF}]|[\x{1F700}-\x{1F77F}]|[\x{1F780}-\x{1F7FF}]|[\x{1F800}-\x{1F8FF}]|[\x{1F900}-\x{1F9FF}]|[\x{1FA00}-\x{1FA6F}]|[\x{1FA70}-\x{1FAFF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u',
            '',
            $texte
        );
    }

    /**
     * Gérer les messages texte
     */
    private function handleTextMessage(array $message): void
    {
        
        $text = $message['text']['body'] ?? '';
        $from = $message['from'] ?? '';
        
        Log::info('Message texte reçu', [
            'from' => $from,
            'text' => $text
        ]);


        $session = $this->getSession($from);

        // Logique métier pour les messages texte
        // Exemple: commandes, chatbot, etc.
        if (strtolower($text) === 'help') {
            $this->sendHelpMessage($from);
        } elseif (strtolower($text) === 'menu' || strtolower($text) == '📋 menu') {
            $this->sendMenuMessage($from);
        } elseif (strtolower($text) === 'services') {
            $this->sendServicesListMessage($from);
        } elseif($this->estNumeriqueEtLongueurSup5($text)){
            $this->handleAccount($from, $text, $session);
        }elseif($this->estNumeriqueEtLongueurInf5($text)){
            $this->handleOtp($from, $text, $session);
        }  
        else {
            $this->sendMenuMessage($from);
        }
        
        // Traitement des réponses aux boutons interactifs
        if (isset($message['interactive'])) {
            Log::info('seans'.$message['interactive']);
            $this->handleInteractiveResponse($message);
        }
        
        // Sauvegarder en base de données si nécessaire
        // $this->saveMessage($message);
    }
    
    /**
     * Gérer les messages image
     */
    private function handleImageMessage(array $message): void
    {
        $caption = $message['image']['caption'] ?? '';
        $mediaUrl = $message['image']['link'] ?? '';
        
        Log::info('Image reçue', [
            'from' => $message['from'] ?? '',
            'caption' => $caption,
            'url' => $mediaUrl
        ]);
        
        // Traitement des images (téléchargement, analyse, etc.)
    }
    
    /**
     * Gérer les messages document
     */
    private function handleDocumentMessage(array $message): void
    {
        $filename = $message['document']['filename'] ?? '';
        $mediaUrl = $message['document']['link'] ?? '';
        
        Log::info('Document reçu', [
            'from' => $message['from'] ?? '',
            'filename' => $filename,
            'url' => $mediaUrl
        ]);
    }
    
    /**
     * Gérer les messages audio
     */
    private function handleAudioMessage(array $message): void
    {
        $mediaUrl = $message['audio']['link'] ?? '';
        
        Log::info('Audio reçu', [
            'from' => $message['from'] ?? '',
            'url' => $mediaUrl
        ]);
    }
    
    /**
     * Gérer les messages vidéo
     */
    private function handleVideoMessage(array $message): void
    {
        $caption = $message['video']['caption'] ?? '';
        $mediaUrl = $message['video']['link'] ?? '';
        
        Log::info('Vidéo reçue', [
            'from' => $message['from'] ?? '',
            'caption' => $caption,
            'url' => $mediaUrl
        ]);
    }
    
    /**
     * Gérer les messages de localisation
     */
    private function handleLocationMessage(array $message): void
    {
        $latitude = $message['location']['latitude'] ?? '';
        $longitude = $message['location']['longitude'] ?? '';
        
        Log::info('Localisation reçue', [
            'from' => $message['from'] ?? '',
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }
    
    /**
     * Gérer les messages contact
     */
    private function handleContactMessage(array $message): void
    {
        $contacts = $message['contacts'] ?? [];
        
        Log::info('Contact reçu', [
            'from' => $message['from'] ?? '',
            'contacts' => $contacts
        ]);
    }
    
    /**
     * Gérer les statuts de messages
     */
    private function handleMessageStatus(array $status): void
    {
        $messageId = $status['id'] ?? '';
        $statusType = $status['status'] ?? '';
        $recipient = $status['recipient_id'] ?? '';
        
        Log::info('Statut message reçu', [
            'message_id' => $messageId,
            'status' => $statusType,
            'recipient' => $recipient
        ]);
        
        // Traitement selon le statut
        switch ($statusType) {
            case 'sent':
                // Message envoyé
                break;
            case 'delivered':
                // Message livré
                break;
            case 'read':
                // Message lu
                break;
            case 'failed':
                // Échec d'envoi
                Log::error('Échec envoi message', ['message_id' => $messageId]);
                break;
        }
    }
    
    /**
     * Gérer les événements de chat
     */
    private function handleChatEvent(array $chat): void
    {
        Log::info('Événement chat reçu', $chat);
        
        // Traitement des événements de chat
        $chatId = $chat['id'] ?? '';
        $eventType = $chat['event_type'] ?? '';
        
        switch ($eventType) {
            case 'chat_opened':
                Log::info('Chat ouvert', ['chat_id' => $chatId]);
                break;
            case 'chat_closed':
                Log::info('Chat fermé', ['chat_id' => $chatId]);
                break;
            case 'typing':
                Log::info('En cours de frappe', ['chat_id' => $chatId]);
                break;
            case 'presence_update':
                $presence = $chat['presence'] ?? '';
                Log::info('Mise à jour présence', [
                    'chat_id' => $chatId,
                    'presence' => $presence
                ]);
                break;
            default:
                Log::info('Événement chat non géré', [
                    'event_type' => $eventType,
                    'chat' => $chat
                ]);
        }
    }

    /**
     * Gérer les événements de contact
     */
    private function handleContactEvent(array $contact): void
    {
        Log::info('Événement contact reçu', $contact);
        
        // Traitement des événements de contact
        // (mise à jour du profil, statut en ligne, etc.)
    }
    
    /**
     * Envoyer un message de réponse automatique
     */
    private function sendAutoReply(string $to, array $originalMessage): void
    {
        // Configuration de base
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/messages/text', [
                'to' => $to,
                'body' => 'Merci pour votre message ! Nous vous répondrons bientôt.'
            ]);
            
            if ($response->successful()) {
                Log::info('Réponse automatique envoyée', ['to' => $to]);
            } else {
                Log::error('Erreur envoi réponse automatique', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception envoi réponse automatique', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
        }
    }
    
    /**
     * Envoyer un message d'aide avec quick reply
     */
    private function sendHelpMessage(string $to): void
    {
        $helpText = "🤖 *Bienvenue !*Comment puis-je vous aider aujourd'hui ?";
        
        $quickReplies = [
            ['id' => 'info', 'title' => 'ℹ️ Informations'],
            ['id' => 'services', 'title' => '🛍️ Nos Services'],
            ['id' => 'contact', 'title' => '📞 Contact']
        ];
        
        $this->sendInteractiveMessage($to, $helpText, $quickReplies);
    }
    
    /**
     * Envoyer un message de menu avec quick reply
     */
    private function sendMenuMessage(string $to): void
    {
        $menuText = "*Menu Principal*, Choisissez une option :";
        
        $quickReplies = [
            ['id' => 'menu', 'title' => '📋 Menu'],
            ['id' => 'solde', 'title' => '💰 Solde'],
            // ['id' => 'historique', 'title' => '📜 Historique'],
            ['id' => 'quit', 'title' => '🚪 Quitter']
        ];
        
        $this->sendInteractiveMessage($to, $menuText, $quickReplies);
    }
    
    /**
     * Envoyer une liste de services
     */
    private function sendServicesListMessage(string $to): void
    {
        $bodyText = "🛍️ *Nos Services* Voici ce que nous proposons :";
        $buttonText = "Choisir un service";
        
        $sections = [
            [
                'title' => 'Services Principaux',
                'rows' => [
                    [
                        'id' => 'service_1',
                        'title' => 'Consultation',
                        'description' => 'Consultation personnalisée'
                    ],
                    [
                        'id' => 'service_2',
                        'title' => 'Formation',
                        'description' => 'Formation professionnelle'
                    ],
                    [
                        'id' => 'service_3',
                        'title' => 'Support Technique',
                        'description' => 'Assistance technique 24/7'
                    ]
                ]
            ],
            [
                'title' => 'Services Premium',
                'rows' => [
                    [
                        'id' => 'premium_1',
                        'title' => 'Développement Custom',
                        'description' => 'Solutions sur mesure'
                    ],
                    [
                        'id' => 'premium_2',
                        'title' => 'Maintenance',
                        'description' => 'Maintenance complète'
                    ]
                ]
            ]
        ];
        
        $this->sendListMessage($to, $bodyText, $buttonText, $sections);
    }
    
    /**
     * Envoyer un message interactif avec quick reply
     */
    public function sendInteractiveMessage(string $to, string $bodyText, array $quickReplies): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }
        
        // Construire les boutons de quick reply
        $buttons = [];
        foreach ($quickReplies as $index => $reply) {
            $buttons[] =
                [
                    'type' => 'quick_reply',
                    'id' => is_array($reply) ? $reply['id'] : $reply,
                    'title' => is_array($reply) ? $reply['title'] : $reply,
                ];
        }
        

        // 
        // "header"=> [
        //     "type" => "image",
        //     "media" => [
        //         "link" => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/960px-Cat_November_2010-1a.jpg"
        //     ]
        // ],
        
        $payload = [
            'to' => $to,
            "media"=> "https://i.ibb.co/9HdWW5M5/logo-bgfibank-two.jpg",
            "caption"=> "LOGO BGFIBank",
            "header"=> [
                "text" => "Bienvenue À BGFIBank\n-----------------------\n"
            ],
            "body"=> [
                "text" => "Bienvenue À BGFIBank\n------------------------------\n".$bodyText
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
                Log::info('Message interactif envoyé avec succès', [
                    'to' => $to,
                    'buttons_count' => count($buttons),
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Erreur envoi message interactif', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $payload
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception envoi message interactif', [
                'error' => $e->getMessage(),
                'to' => $to,
                'payload' => $payload
            ]);
        }
    }
    
    public function markMessageAsRead($messageId)
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->put("{$baseUrl}/messages/{$messageId}");

            if ($response->successful()) {
                Log::info("Message {$messageId} marqué comme lu avec succès");
                return true;
            } else {
                Log::error("Erreur lors du marquage comme lu du message {$messageId}: " . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Exception lors du marquage comme lu du message {$messageId}: " . $e->getMessage());
            return false;
        }
    }


    public function sendInteractiveMediaMessages(string $to, string $bodyText, array $quickReplies): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }
        
        // Construire les boutons de quick reply
        $buttons = [];
        foreach ($quickReplies as $index => $reply) {
        
            if(isset($reply['url'])) {
                $buttons[] =
                [
                    'type' => is_array($reply) ? $reply['type'] : 'quick_reply',
                    'id' => is_array($reply) ? $reply['id'] : $reply,
                    'title' => is_array($reply) ? $reply['title'] : $reply,
                    'url' => is_array($reply) ? $reply['url'] : '',
                    
                ];
            } elseif(isset($reply['phone_number'])) {
                $buttons[] =
                [
                    'type' => is_array($reply) ? $reply['type'] : 'quick_reply',
                    'id' => is_array($reply) ? $reply['id'] : $reply,
                    'title' => is_array($reply) ? $reply['title'] : $reply,
                    'phone_number' => is_array($reply) ? $reply['phone_number'] : '',
                    
                ];
            } else {
                $buttons[] =
                [
                    'type' => is_array($reply) ? $reply['type'] : 'quick_reply',
                    'id' => is_array($reply) ? $reply['id'] : $reply,
                    'title' => is_array($reply) ? $reply['title'] : $reply,
                    
                ];
            }

        }
        
        $payload = [
            'to' => $to,
            "media"=> "https://i.ibb.co/9HdWW5M5/logo-bgfibank-two.jpg",
            "caption"=> "LOGO BGFIBank",
            "header"=> [
                "text" => "Bienvenue À BGFIBank\n-----------------------"
            ],
            "body"=> [
                "text" => $bodyText
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
                Log::info('Message interactif envoyé avec succès', [
                    'to' => $to,
                    'buttons_count' => count($buttons),
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Erreur envoi message interactif', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $payload
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception envoi message interactif', [
                'error' => $e->getMessage(),
                'to' => $to,
                'payload' => $payload
            ]);
        }
    }

    /**
     * Envoyer un message avec liste interactive
     */
    public function sendListMessage(string $to, string $bodyText, string $buttonText, array $sections): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }
        
        $payload = [
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'body' => [
                    'text' => $bodyText
                ],
                'action' => [
                    'button' => $buttonText,
                    'sections' => $sections
                ]
            ]
        ];
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/messages/interactive', $payload);
            
            if ($response->successful()) {
                Log::info('Message liste envoyé avec succès', [
                    'to' => $to,
                    'sections_count' => count($sections),
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Erreur envoi message liste', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception envoi message liste', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
        }
    }

    /**
     * Envoyer un message texte
     */
    private function sendTextMessage(string $to, string $text): void
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');
        
        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/messages/text', [
                'to' => $to,
                'body' => $text
            ]);
            
            if ($response->successful()) {
                Log::info('Message envoyé avec succès', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Erreur envoi message', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception envoi message', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
        }
    }
    
    /**
     * Gérer les réponses aux messages interactifs
     */
    private function handleInteractiveResponse(array $message): void
    {
        $from = $message['from'] ?? '';
        

        // Réponse à un bouton
        if (isset($message['reply']['buttons_reply'])) {
            $buttonId = $message['reply']['buttons_reply']['id'] ?? '';
            $buttonTitle = $message['reply']['buttons_reply']['title'] ?? '';
            
            Log::info('Réponse bouton reçue', [
                'from' => $from,
                'button_id' => $buttonId,
                'button_title' => $buttonTitle
            ]);
            
            $this->processButtonResponse($from, $buttonId, $buttonTitle);
        }
        
        // Réponse à une liste
        if (isset($message['interactive']['list_reply'])) {
            $listId = $message['interactive']['list_reply']['id'] ?? '';
            $listTitle = $message['interactive']['list_reply']['title'] ?? '';
            $listDescription = $message['interactive']['list_reply']['description'] ?? '';
            
            Log::info('Réponse liste reçue', [
                'from' => $from,
                'list_id' => $listId,
                'list_title' => $listTitle
            ]);
            
            $this->processListResponse($from, $listId, $listTitle, $listDescription);
        }
    }
    
    /**
     * Traiter les réponses aux boutons
     */
    private function processButtonResponse(string $from, string $buttonId, string $buttonTitle): void
    {

        Log::info($buttonId);
        switch ($buttonTitle) {
            case '💰 Solde':
                $infoText = "ℹ️*Consultatoion Solde Securisé*\n" .
                           "📝 Saisissez votre numéro de compte (10 chiffres)\n💡 Exemple: 1000123456";
                $this->sendTextMessage($from, $infoText);
                break;
            
            case '📋 Menu':
                $this->sendMenuMessage($from);
                break;

            case '🚪 Quitter':
                $catalogueText = "Merci de votre visite chez BGFI Bank, souhaitez-vous nous appeler ou visiter notre site ?";
                $quickReplies = [
                    ['id' => 'call', 'title' => '📞 Appeler BGFI', 'phone_number' => '8080', 'type' => 'call'],
                    ['id' => 'visit', 'title' => '🌐 Visiter le site', 'type' => 'url', 'url' => 'https://www.groupebgfibank.com']
                ];
                $this->sendInteractiveMediaMessages($from, $catalogueText, $quickReplies);
                break;
                
            case '📃 Avoir le Recepisse':
                $session = $this->getSession($from);

                $this->sendPdfMessage($from, $session['file_link']);
                break;
                
            case 'catalogue':
                $catalogueText = "📚 *Notre Catalogue*Voici nos produits disponibles...";
                $quickReplies = [
                    ['id' => 'cat_electronique', 'title' => '💻 Électronique'],
                    ['id' => 'cat_mode', 'title' => '👕 Mode'],
                    ['id' => 'cat_maison', 'title' => '🏠 Maison']
                ];
                $this->sendInteractiveMessage($from, $catalogueText, $quickReplies);
                break;
                
            case 'commande':
                $commandeText = "🛒 *Passer une Commande*Pour commander, envoyez-nous :" .
                               "1. Le nom du produit" .
                               "2. La quantité souhaitée" .
                               "3. Votre adresse de livraison";
                $this->sendTextMessage($from, $commandeText);
                break;
                
            case 'support':
                $supportText = "🆘 *Support Client*Comment pouvons-nous vous aider ?";
                $quickReplies = [
                    ['id' => 'bug_report', 'title' => '🐛 Signaler un Bug'],
                    ['id' => 'question', 'title' => '❓ Poser une Question'],
                    ['id' => 'feedback', 'title' => '💬 Donner un Avis']
                ];
                $this->sendInteractiveMessage($from, $supportText, $quickReplies);
                break;
                
            default:
                $this->sendTextMessage($from, "Merci pour votre choix ! Un conseiller va vous contacter.");
        }
    }

    function estNumeriqueEtLongueurSup5($chaine) {
        return preg_match('/^\d+$/', $chaine) && strlen($chaine) > 5;
    }


    function estNumeriqueEtLongueurInf5($chaine) {
        return preg_match('/^\d+$/', $chaine) && strlen($chaine) < 5;
    }


    private function handleAccount(string $from, string $account, array $session): void
    {
        $account = preg_replace('/[^0-9]/', '', $account);
        
        if (strlen($account) !== 10) {
            $this->sendTextMessage($from,
                "❌ Numéro invalide. Saisissez 10 chiffres.\n📝 Exemple: 1000123456"
            );
            return;
        }
        
        if (!isset($this->accounts[$account])) {
            $attempts = ($session['attempts'] ?? 0) + 1;
            
            if ($attempts >= 3) {

                $catalogueText = "Merci de votre visite chez BGFI Bank, venez et creer votre carte";
                $quickReplies = [
                    ['id' => 'visit', 'title' => '🌐 Ouvrir Un Compte', 'type' => 'url', 'url' => 'https://bgfibankgabon.bgfi.com/particuliers/gerer-ses-comptes/']
                ];

                $this->sendTextMessage($from,
                    "🚫 Trop de tentatives. Contactez le *880*."
                );
                $this->sendMail($from);
                $this->sendInteractiveMediaMessages($from, $catalogueText, $quickReplies);
                $this->updateSession($from, ['state' => 'menu']);
                return;
            }
            
            $this->sendTextMessage($from, "❌ Compte introuvable. Tentative {$attempts}/3");
            $this->updateSession($from, ['state' => 'waiting_account', 'attempts' => $attempts]);
            return;
        }
        
        
        $accountData = $this->accounts[$account];
        $otpSent = $this->sendTwilioOtp($accountData['phone']);
        
        if (!$otpSent) {
            $this->sendTextMessage($from,
                "❌ Erreur envoi OTP. Contactez le *880*."
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        $this->sendTextMessage($from,
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

    private function sendMail($numero){
        $emailEquipe = 'eqc@bgfi.com';
        
        Mail::to($emailEquipe)
            ->cc('prime.esse@jobs-conseil.com')
            ->send(new AlerteProspectMail($numero, 'CRM Banque'));
    }
    
    private function showBalance(string $from, array $account, string $accountNumber): void
    {
        $balance = number_format($account['balance'], 0, ',', ' ');
        
        $session = $this->getSession($from);

        // $this->sendTextMessage($from,
        //     "💰 **SOLDE COMPTE**\n\n" .
        //     "👤 {$account['name']}\n" .
        //     "🏦 " . $this->maskAccount($accountNumber) . "\n\n" .
        //     "💵 **{$balance} {$account['currency']}**\n\n" .
        //     "📅 " . date('d/m/Y à H:i') . "\n" .
        //     "✅ BGFI Bank vous remercie !"
        // );
        
        $menuText = "💰 **SOLDE COMPTE**\n" .
            "👤 {$account['name']}\n------------------------------\n" .
            "🏦 " . $this->maskAccount($accountNumber) . "\n------------------------------\n" .
            "💵 **{$balance} {$account['currency']}**\n------------------------------\n" .
            "📅 " . date('d/m/Y à H:i') . "\n------------------------------\n" .
            "✅ BGFI Bank vous remercie !";
        
        $quickReplies = [
            ['id' => 'recepisse', 'title' => '📃 Avoir le Recepisse'],
            ['id' => 'menu', 'title' => '📋 Menu']
        ];
        
        $this->sendInteractiveMessage($from, $menuText, $quickReplies);
        $this->generateReleve($from, $balance, $session);

    }

    private function handleOtp(string $from, string $code, array $session): void
    {
        $code = preg_replace('/[^0-9]/', '', $code);
        $attempts = ($session['otp_attempts'] ?? 0) + 1;
        $phone = $session['phone'] ?? '';
        $account = $session['account'] ?? '';
        
        Log::info([$phone, $account, $code]);

        if (strlen($code) < 4) {
            $this->sendTextMessage($from, "❌ Code trop court. Saisissez le code complet:");
            return;
        }
        
        $result = $this->verifyTwilioOtp($phone, $code);
        
        if ($result === 'approved') {
            $accountData = $this->accounts[$account];
            $this->showBalance($from, $accountData, $account);
            // $this->generateReleve($from, $amount);
            // $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        if ($attempts >= 3) {
            $this->sendTextMessage($from,
                "🚫 Accès bloqué après 3 tentatives.\n📞 Contactez le *880*\n\nRetour au menu..."
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        if ($result === 'expired') {
            $this->sendTextMessage($from,
                "⏰ Code expiré. Tapez '2' pour relancer.\n\nRetour au menu..."
            );
            $this->sendMenuMessage($from);
            $this->updateSession($from, ['state' => 'menu']);
            return;
        }
        
        $remaining = 3 - $attempts;
        $this->sendTextMessage($from,
            "❌ Code incorrect. Tentative {$attempts}/3\n🔄 {$remaining} essais restants"
        );
        
        $this->updateSession($from, array_merge($session, ['otp_attempts' => $attempts]));
    }

    private function sendPdfMessage(string $to, string $filename)
    {
        $token = config('waapi.whapi_token');
        $baseUrl = config('waapi.whapi_base_url', 'https://gate.whapi.cloud');

        if (!$token) {
            Log::warning('Token Whapi non configuré');
            return;
        }

        try {
            $response = Http::withToken($token)
                ->attach('media', file_get_contents($filename), basename($filename))
                ->post($baseUrl . '/messages/document', [ // ✅ bon endpoint
                    'to' => $to,
                    'caption' => 'Voici votre relevé de compte BGFI.',
                ]);

            if ($response->successful()) {
                Log::info('Message WhatsApp envoyé avec succès', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Échec d\'envoi du message WhatsApp', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Exception lors de l\'envoi WhatsApp', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
        }
    }

    
    public function generateReleve(string $to, string $solde, array $session)
    {

        $data = [
            'solde' => $solde . 'Fcfa',
            'date' => now()->addHour()->format('d/m/Y'),
            'dateHeure' => now()->addHour()->format('d/m/Y à H:i:s'),
        ];

        $pdf = PDF::loadView('pdf.position-compte', $data)
                  ->setPaper('A4', 'landscape');

        // Nom du fichier PDF
        $fileName = 'releve-' . now()->format('Ymd-His') . '.pdf';

        // Chemin de sauvegarde dans storage/app/public/
        Storage::disk('public')->put('soldes/'.$fileName, $pdf->output());

        // URL publique (si le disque 'public' est correctement configuré)
        $filePath = storage_path('app/public/soldes/' . $fileName);

        $this->updateSession($to, array_merge($session, ['file_link' => $filePath]));

        // $this->sendPdfMessage($to, $filePath);

        // return response()->json(['status' => 'PDF envoyé avec succès.']);
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
            
            
            Log::info($formattedPhone);

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
        Log::info('avant verification'.$phone);
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        $clean = '0'.substr($clean, 3);

        Log::info('avant verification clean'.$phone);

        if (str_starts_with($clean, '0')) {
            Log::info('clean1'.$clean);

            return '+241' . substr($clean, 1);
        }
        
        if (!str_starts_with($clean, '+')) {
        Log::info('clean2'.$clean);

            return str_starts_with($clean, '241') ? '+' . $clean : '+241' . $clean;
        }

        Log::info('clean'.$clean);

        
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
            'otp_attempts' => 0,
            'file_link' => ''
        ]);
    }
    
   
    private function updateSession(string $from, array $data): void
    {
        $session = array_merge($this->getSession($from), $data);
        Cache::put("bgfi_session_{$from}", $session, 1800); // 30 min
    }
    

    /**
     * Traiter les réponses aux listes
     */
    private function processListResponse(string $from, string $listId, string $listTitle, string $listDescription): void
    {
        switch ($listId) {
            case 'service_1':
            case 'service_2':
            case 'service_3':
                $serviceText = "✅ *Service Sélectionné: {$listTitle}*" .
                              "{$listDescription}" .
                              "Souhaitez-vous plus d'informations ou prendre rendez-vous ?";
                $quickReplies = [
                    ['id' => 'more_info', 'title' => 'ℹ️ Plus d\'infos'],
                    ['id' => 'book_appointment', 'title' => '📅 Rendez-vous'],
                    ['id' => 'back_menu', 'title' => '↩️ Retour Menu']
                ];
                $this->sendInteractiveMessage($from, $serviceText, $quickReplies);
                break;
                
            case 'premium_1':
            case 'premium_2':
                $premiumText = "⭐ *Service Premium: {$listTitle}*" .
                              "{$listDescription}" .
                              "Ce service nécessite une consultation préalable.";
                $quickReplies = [
                    ['id' => 'premium_consult', 'title' => '📞 Consultation'],
                    ['id' => 'premium_quote', 'title' => '💰 Devis'],
                    ['id' => 'back_services', 'title' => '↩️ Retour Services']
                ];
                $this->sendInteractiveMessage($from, $premiumText, $quickReplies);
                break;
                
            default:
                $this->sendTextMessage($from, "Merci pour votre sélection : {$listTitle}");
        }
    }
    // {
    //     // Exemple de sauvegarde en base de données
    //     /*
    //     try {
    //         WhatsAppMessage::create([
    //             'whapi_id' => $message['id'] ?? '',
    //             'from' => $message['from'] ?? '',
    //             'to' => $message['to'] ?? '',
    //             'body' => $message['text']['body'] ?? '',
    //             'type' => $message['type'] ?? 'text',
    //             'whapi_timestamp' => date('Y-m-d H:i:s', $message['timestamp'] ?? time())
    //         ]);
            
    //         Log::info('Message sauvegardé en base');
            
    //     } catch (\Exception $e) {
    //         Log::error('Erreur sauvegarde message', [
    //             'error' => $e->getMessage(),
    //             'message' => $message
    //         ]);
    //     }
    //     */
    // }
}
