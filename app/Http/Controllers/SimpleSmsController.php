<?php
// app/Http/Controllers/SimpleSmsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vonage\Client;
use Vonage\Client\Credentials\Keypair;
use Vonage\Messages\Channel\SMS\SMSText;

class SimpleSmsController extends Controller
{
    private $vonage;

    public function __construct()
    {
        // Configuration Vonage avec clé privée
        $credentials = new Keypair(
            file_get_contents(base_path(env('VONAGE_PRIVATE_KEY'))),
            env('VONAGE_APPLICATION_ID')
        );

        $this->vonage = new Client($credentials);
    }

    /**
     * Envoyer un SMS
     * GET /sms/send/{phone}/{message}
     * POST /sms/send avec phone et message
     */
    public function send(Request $request, $phone = null, $message = null)
    {
        // Récupérer phone et message depuis URL ou POST
        $phone = $phone ?? $request->input('phone');
        $message = $message ?? $request->input('message');

        if (!$phone || !$message) {
            return response()->json(['error' => 'Phone et message requis'], 400);
        }

        try {
            // Créer et envoyer le SMS
            $sms = new SMSText($phone, env('VONAGE_FROM_NUMBER', 'Laravel'), $message);
            $response = $this->vonage->messages()->send($sms);

            // Log de succès
            \Log::info("✅ SMS envoyé à $phone: $message");

            return response()->json([
                'success' => true,
                'message' => 'SMS envoyé avec succès',
                'uuid' => $response->getMessageUuid(),
                'to' => $phone
            ]);

        } catch (\Exception $e) {
            \Log::error("❌ Erreur SMS: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recevoir un SMS (Webhook Vonage)
     * POST /sms/webhook
     */
    public function webhook(Request $request)
    {
        // Récupérer les données du SMS entrant
        $from = $request->input('from.number');
        $to = $request->input('to.number');
        $text = $request->input('message.content.text');
        $uuid = $request->input('message_uuid');

        // Log du message reçu
        \Log::info("📱 SMS reçu de $from: $text");

        // Réponse automatique simple
        $this->autoReply($from, $text);

        // IMPORTANT: Retourner 200 OK
        return response('OK', 200);
    }

    /**
     * Réponse automatique
     */
    private function autoReply($phone, $receivedMessage)
    {
        $message = strtolower(trim($receivedMessage));
        $response = '';

        // Logique de réponse simple
        switch ($message) {
            case 'hello':
            case 'bonjour':
            case 'salut':
                $response = "👋 Bonjour ! Tapez 'aide' pour voir les options.";
                break;

            case 'aide':
            case 'help':
                $response = "🔹 bonjour - Salutation\n🔹 info - Informations\n🔹 contact - Nous contacter\n🔹 stop - Arrêter";
                break;

            case 'info':
                $response = "ℹ️ Notre service SMS automatisé fonctionne 24h/7j !";
                break;

            case 'contact':
                $response = "📞 Email: contact@exemple.com\nTel: 01.23.45.67.89";
                break;

            case 'stop':
                $response = "❌ Vous êtes désabonné. Tapez START pour reprendre.";
                break;

            case 'start':
                $response = "✅ Réabonné ! Tapez 'aide' pour les options.";
                break;

            default:
                $response = "❓ Je n'ai pas compris '$receivedMessage'. Tapez 'aide' pour les commandes.";
        }

        // Envoyer la réponse
        if ($response) {
            try {
                $sms = new SMSText($phone, env('VONAGE_FROM_NUMBER', 'Laravel'), $response);
                $this->vonage->messages()->send($sms);
                \Log::info("🤖 Réponse auto envoyée à $phone: $response");
            } catch (\Exception $e) {
                \Log::error("❌ Erreur réponse auto: " . $e->getMessage());
            }
        }
    }

    /**
     * Statut des messages (Webhook Vonage)
     * POST /sms/status
     */
    public function status(Request $request)
    {
        $uuid = $request->input('message_uuid');
        $status = $request->input('status');

        \Log::info("📊 Statut message $uuid: $status");

        return response('OK', 200);
    }

    /**
     * Interface de test simple
     * GET /sms/test
     */
    public function testForm()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Test SMS Simple</title>
            <style>
                body { font-family: Arial; max-width: 500px; margin: 50px auto; padding: 20px; }
                input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
                button { background: #007cba; color: white; padding: 12px 20px; border: none; cursor: pointer; width: 100%; }
                .result { margin: 20px 0; padding: 10px; border-radius: 5px; }
                .success { background: #d4edda; color: #155724; }
                .error { background: #f8d7da; color: #721c24; }
            </style>
        </head>
        <body>
            <h1>🚀 Test SMS Simple</h1>

            <form onsubmit="sendSms(event)">
                <input type="text" id="phone" placeholder="+33612345678" required>
                <textarea id="message" placeholder="Votre message..." required></textarea>
                <button type="submit">📤 Envoyer SMS</button>
            </form>

            <div id="result"></div>

            <h3>📋 Tests rapides:</h3>
            <button onclick="quickTest(\'+33612345678\', \'bonjour\')">Test Bonjour</button>
            <button onclick="quickTest(\'+33612345678\', \'aide\')">Test Aide</button>

            <script>
            function sendSms(e) {
                e.preventDefault();
                const phone = document.getElementById("phone").value;
                const message = document.getElementById("message").value;
                quickTest(phone, message);
            }

            function quickTest(phone, message) {
                fetch("/sms/send", {
                    method: "POST",
                    headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": "' . csrf_token() . '"},
                    body: JSON.stringify({phone: phone, message: message})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("result").innerHTML =
                            `<div class="result success">✅ SMS envoyé ! UUID: ${data.uuid}</div>`;
                    } else {
                        document.getElementById("result").innerHTML =
                            `<div class="result error">❌ Erreur: ${data.error}</div>`;
                    }
                })
                .catch(error => {
                    document.getElementById("result").innerHTML =
                        `<div class="result error">❌ Erreur: ${error}</div>`;
                });
            }
            </script>
        </body>
        </html>';
    }
}
