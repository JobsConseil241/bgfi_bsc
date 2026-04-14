<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private $authKey;
    private $senderId;
    private $baseUrl = 'https://api.msg91.com/api/';

    public function __construct()
    {
        $this->authKey = config('services.msg91.auth_key');
        $this->senderId = config('services.msg91.sender_id');
    }

    /**
     * Recevoir et traiter les messages entrants
     */
    public function handleIncomingMessage(Request $request)
    {
        try {
            Log::info('Message chatbot reçu:', $request->all());

            // Récupérer les données du message
            $mobile = $this->formatMobile($request->input('mobile') ?? $request->input('from'));
            $message = trim($request->input('message') ?? $request->input('text'));
            $keyword = $request->input('keyword');

            if (empty($mobile) || empty($message)) {
                return response()->json(['error' => 'Données invalides'], 400);
            }

            // Traiter le message et générer une réponse
            $response = $this->processMessage($mobile, $message, $keyword);

            // Envoyer la réponse
            if ($response) {
                $this->sendSMS($mobile, $response);
            }

            return response()->json([
                'status' => 'success',
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur chatbot:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Traiter le message et générer une réponse
     */
    private function processMessage($mobile, $message, $keyword = null)
    {
        // Récupérer ou créer une session
        $session = $this->getOrCreateSession($mobile);
        
        // Enregistrer le message entrant
        $this->saveMessage($session->id, 'incoming', $message, $keyword);

        // Nettoyer le message
        $cleanMessage = strtolower(trim($message));

        // Générer la réponse
        $response = $this->generateResponse($session, $cleanMessage);

        // Enregistrer la réponse
        if ($response) {
            $this->saveMessage($session->id, 'outgoing', $response);
        }

        return $response;
    }

    /**
     * Récupérer ou créer une session de chat
     */
    private function getOrCreateSession($mobile)
    {
        // Chercher une session active
        $session = ChatSession::where('mobile', $mobile)
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            // Créer nouvelle session
            $session = ChatSession::create([
                'mobile' => $mobile,
                'state' => 'welcome',
                'context' => [],
                'expires_at' => now()->addMinutes(30)
            ]);
        } else {
            // Prolonger la session
            $session->update(['expires_at' => now()->addMinutes(30)]);
        }

        return $session;
    }

    /**
     * Générer une réponse selon l'état et le message
     */
    private function generateResponse($session, $message)
    {
        // Commandes globales
        if (in_array($message, ['aide', 'help', '?'])) {
            return $this->getHelpMessage();
        }

        if (in_array($message, ['menu', 'accueil', '0'])) {
            $session->update(['state' => 'main_menu']);
            return $this->getMainMenu();
        }

        if (in_array($message, ['stop', 'arrêt', 'quit'])) {
            $session->update(['state' => 'stopped', 'expires_at' => now()]);
            return "👋 Conversation terminée. Merci!\nTapez MENU pour recommencer.";
        }

        if (in_array($message, ['restart', 'recommencer'])) {
            $session->update(['state' => 'welcome', 'context' => []]);
            return "🔄 Session redémarrée.\n\n" . $this->getMainMenu();
        }

        // Réponses selon l'état
        switch ($session->state) {
            case 'welcome':
                return $this->handleWelcome($session);

            case 'main_menu':
                return $this->handleMainMenu($session, $message);

            case 'products':
                return $this->handleProducts($session, $message);

            case 'order_tracking':
                return $this->handleOrderTracking($session, $message);

            case 'support':
                return $this->handleSupport($session, $message);

            case 'contact_info':
                return $this->handleContactInfo($session);

            default:
                return $this->handleUnknown($session);
        }
    }

    /**
     * Message d'accueil
     */
    private function handleWelcome($session)
    {
        $session->update(['state' => 'main_menu']);
        return "👋 Bienvenue sur notre chatbot!\n\n" . $this->getMainMenu();
    }

    /**
     * Menu principal
     */
    private function getMainMenu()
    {
        return "🏠 MENU PRINCIPAL\n\n" .
               "1️⃣ Nos produits\n" .
               "2️⃣ Suivi de commande\n" .
               "3️⃣ Support client\n" .
               "4️⃣ Nos horaires\n" .
               "5️⃣ Contact\n\n" .
               "Tapez le numéro de votre choix";
    }

    /**
     * Gérer les choix du menu principal
     */
    private function handleMainMenu($session, $message)
    {
        switch ($message) {
            case '1':
                $session->update(['state' => 'products']);
                return "🛍️ NOS PRODUITS\n\n" .
                       "Catégories disponibles:\n" .
                       "A) Électronique\n" .
                       "B) Vêtements\n" .
                       "C) Maison & Jardin\n" .
                       "D) Sports\n\n" .
                       "Tapez la lettre de votre choix";

            case '2':
                $session->update(['state' => 'order_tracking']);
                return "📦 SUIVI DE COMMANDE\n\n" .
                       "Veuillez saisir votre numéro de commande\n" .
                       "(Format: CMD123456)";

            case '3':
                $session->update(['state' => 'support']);
                return "🎧 SUPPORT CLIENT\n\n" .
                       "Décrivez votre problème en quelques mots.\n" .
                       "Notre équipe vous contactera rapidement.";

            case '4':
                return "🕒 NOS HORAIRES\n\n" .
                       "📅 Lundi - Vendredi: 8h - 18h\n" .
                       "📅 Samedi: 9h - 17h\n" .
                       "📅 Dimanche: Fermé\n\n" .
                       "Tapez MENU pour revenir";

            case '5':
                $session->update(['state' => 'contact_info']);
                return $this->getContactInfo();

            default:
                return "❌ Choix invalide. Tapez un numéro entre 1 et 5.\n\n" . $this->getMainMenu();
        }
    }

    /**
     * Gérer les produits
     */
    private function handleProducts($session, $message)
    {
        $categories = [
            'a' => [
                'name' => 'Électronique',
                'products' => [
                    '📱 Smartphone XYZ - 150 000 FCFA',
                    '💻 Laptop ABC - 350 000 FCFA',
                    '🎧 Écouteurs Bluetooth - 25 000 FCFA',
                    '📺 TV Smart 43" - 200 000 FCFA'
                ]
            ],
            'b' => [
                'name' => 'Vêtements',
                'products' => [
                    '👕 T-shirt coton - 8 000 FCFA',
                    '👖 Jean slim - 15 000 FCFA',
                    '👗 Robe été - 12 000 FCFA',
                    '👟 Baskets sport - 35 000 FCFA'
                ]
            ],
            'c' => [
                'name' => 'Maison & Jardin',
                'products' => [
                    '🏠 Aspirateur - 75 000 FCFA',
                    '🌱 Plantes décoratives - 5 000 FCFA',
                    '🔧 Kit outils - 20 000 FCFA',
                    '🛏️ Parure de lit - 18 000 FCFA'
                ]
            ],
            'd' => [
                'name' => 'Sports',
                'products' => [
                    '👟 Chaussures running - 45 000 FCFA',
                    '⚽ Ballon football - 8 000 FCFA',
                    '🧘 Tapis yoga - 15 000 FCFA',
                    '🏀 Basket - 12 000 FCFA'
                ]
            ]
        ];

        $choice = strtolower($message);

        if (isset($categories[$choice])) {
            $category = $categories[$choice];
            $productList = implode("\n", $category['products']);
            
            return "✅ {$category['name']}\n\n" .
                   "Produits populaires:\n" .
                   "$productList\n\n" .
                   "💻 Plus d'infos sur notre site\n" .
                   "Tapez MENU pour revenir";
        }

        return "❌ Catégorie invalide.\n" .
               "Tapez A, B, C ou D\n" .
               "Ou MENU pour revenir";
    }

    /**
     * Gérer le suivi de commande
     */
    private function handleOrderTracking($session, $message)
    {
        // Vérifier le format du numéro de commande
        if (preg_match('/^CMD[0-9]{6}$/i', strtoupper($message))) {
            $orderNumber = strtoupper($message);
            
            // Simulation du statut (ici vous interrogeriez votre BDD)
            $statuses = [
                'En préparation',
                'Expédiée',
                'En transit',
                'En cours de livraison',
                'Livrée'
            ];
            $randomStatus = $statuses[array_rand($statuses)];
            
            return "📦 COMMANDE $orderNumber\n\n" .
                   "📋 Statut: $randomStatus\n" .
                   "🚚 Transporteur: DHL Express\n" .
                   "📅 Livraison prévue: " . now()->addDays(rand(1,3))->format('d/m/Y') . "\n\n" .
                   "Tapez MENU pour autres services";
        }

        return "❌ Numéro invalide\n" .
               "Format attendu: CMD123456\n" .
               "Réessayez ou tapez MENU";
    }

    /**
     * Gérer le support
     */
    private function handleSupport($session, $message)
    {
        // Enregistrer la demande dans le contexte
        $context = $session->context ?? [];
        $context['support_request'] = $message;
        $context['ticket_id'] = 'TIC' . rand(100000, 999999);
        $session->update(['context' => $context]);

        return "✅ DEMANDE ENREGISTRÉE\n\n" .
               "🎫 Ticket: {$context['ticket_id']}\n" .
               "📝 Votre message: \"$message\"\n\n" .
               "⏰ Réponse sous 24h\n" .
               "📧 Par email ou SMS\n\n" .
               "Tapez MENU pour autres services";
    }

    /**
     * Informations de contact
     */
    private function getContactInfo()
    {
        return "📞 NOUS CONTACTER\n\n" .
               "📧 Email: contact@entreprise.ga\n" .
               "☎️ Tél: +241 XX XX XX XX\n" .
               "📱 WhatsApp: +241 XX XX XX XX\n" .
               "🌐 Site: www.entreprise.ga\n" .
               "📍 Libreville, Gabon\n\n" .
               "Tapez MENU pour revenir";
    }

    /**
     * Message d'aide
     */
    private function getHelpMessage()
    {
        return "🤖 AIDE CHATBOT\n\n" .
               "Commandes disponibles:\n" .
               "• MENU - Menu principal\n" .
               "• AIDE - Cette aide\n" .
               "• STOP - Arrêter\n" .
               "• RESTART - Recommencer\n\n" .
               "Tapez MENU pour commencer";
    }

    /**
     * Message pour commande inconnue
     */
    private function handleUnknown($session)
    {
        return "❓ Je n'ai pas compris.\n\n" .
               "Tapez:\n" .
               "• MENU - Menu principal\n" .
               "• AIDE - Commandes disponibles\n" .
               "• STOP - Terminer";
    }

    /**
     * Envoyer un SMS via MSG91
     */
    private function sendSMS($mobile, $message)
    {
        try {
            $response = Http::get($this->baseUrl . 'sendhttp.php', [
                'authkey' => $this->authKey,
                'mobiles' => $mobile,
                'message' => $message,
                'sender' => $this->senderId,
                'route' => 4,
                'response' => 'json'
            ]);

            if ($response->successful()) {
                Log::info("SMS envoyé avec succès à $mobile");
                return true;
            } else {
                Log::error("Erreur envoi SMS: " . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Exception envoi SMS: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sauvegarder un message
     */
    private function saveMessage($sessionId, $direction, $content, $keyword = null)
    {
        ChatMessage::create([
            'session_id' => $sessionId,
            'direction' => $direction,
            'content' => $content,
            'keyword' => $keyword,
            'processed_at' => $direction === 'incoming' ? now() : null,
            'sent_at' => $direction === 'outgoing' ? now() : null
        ]);
    }

    /**
     * Formater le numéro de téléphone
     */
    private function formatMobile($mobile)
    {
        // Supprimer tout sauf les chiffres
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        
        // Ajouter le code pays si nécessaire (Gabon +241)
        if (strlen($mobile) === 8 && !str_starts_with($mobile, '241')) {
            $mobile = '241' . $mobile;
        }
        
        return $mobile;
    }

    /**
     * API pour déclencher manuellement le chatbot (optionnel)
     */
    public function startChat(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string'
        ]);

        $mobile = $this->formatMobile($request->mobile);
        
        // Créer une nouvelle session
        $session = ChatSession::create([
            'mobile' => $mobile,
            'state' => 'welcome',
            'context' => [],
            'expires_at' => now()->addMinutes(30)
        ]);

        // Envoyer le message d'accueil
        $welcomeMessage = "👋 Bienvenue!\n\n" . $this->getMainMenu();
        $this->sendSMS($mobile, $welcomeMessage);
        $this->saveMessage($session->id, 'outgoing', $welcomeMessage);

        return response()->json([
            'status' => 'success',
            'message' => 'Chat démarré',
            'session_id' => $session->id
        ]);
    }

    /**
     * Obtenir l'historique d'une conversation (optionnel)
     */
    public function getChatHistory(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string'
        ]);

        $mobile = $this->formatMobile($request->mobile);
        
        $sessions = ChatSession::where('mobile', $mobile)
            ->with('messages')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'sessions' => $sessions
        ]);
    }
}
