<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vonage\Laravel\Facade\Vonage;
use Vonage\SMS\Message\SMS;


class sendSMSVonage extends Controller
{
    // Recevoir les SMS via webhook
    public function receiveWebhook(Request $request)
    {
        $from = $request->input('msisdn');  // Numéro expéditeur
        $text = $request->input('text');    // Message reçu
        $messageId = $request->input('messageId');

        // Log du message reçu
        Log::info('SMS reçu', [
            'from' => $from,
            'message' => $text,
            'id' => $messageId
        ]);

        // Générer réponse automatique
        $response = $this->getAutoResponse($text);

        // Envoyer la réponse
        if ($response) {
            $this->sendSms($from, $response);
        }

        return response('OK', 200);
    }

    // Logique de réponse automatique
    private function getAutoResponse($message)
    {
        $message = strtolower(trim($message));

        switch ($message) {
            case 'bonjour':
            case 'salut':
            case 'hello':
                return "👋 Bonjour ! Comment puis-je vous aider ?\n\n1️⃣ Informations\n2️⃣ Support\n3️⃣ Contact\n4️⃣ Horaires";

            case '1':
                return "📋 Nos services :\n- Développement web\n- Applications mobiles\n- Consulting IT\n\nTapez 'menu' pour revenir";

            case '2':
                return "🛠️ Support technique :\n- Email: support@exemple.com\n- Tel: 01.23.45.67.89\n- Disponible 9h-18h\n\nTapez 'menu' pour revenir";

            case '3':
                return "📞 Nous contacter :\n- Email: contact@exemple.com\n- Tel: 01.23.45.67.89\n- Adresse: 123 Rue Example, Paris\n\nTapez 'menu' pour revenir";

            case '4':
                return "🕒 Nos horaires :\n- Lun-Ven: 9h00-18h00\n- Sam: 9h00-12h00\n- Dim: Fermé\n\nTapez 'menu' pour revenir";

            case 'menu':
                return "📋 Menu principal :\n\n1️⃣ Informations\n2️⃣ Support\n3️⃣ Contact\n4️⃣ Horaires";

            case 'stop':
            case 'arret':
                return "❌ Vous êtes désabonné.\nTapez START pour reprendre les notifications.";

            case 'start':
                return "✅ Réabonné avec succès !\nTapez 'bonjour' pour commencer.";

            case 'aide':
            case 'help':
                return "❓ Commandes disponibles :\n- bonjour : Menu principal\n- stop : Se désabonner\n- start : Se réabonner\n- aide : Cette aide";

            default:
                if (strlen($message) > 50) {
                    return "📝 Message long reçu. Un conseiller vous contactera sous 24h.\nTapez 'menu' pour les options rapides.";
                }
                return "❓ Je n'ai pas compris '$message'.\nTapez 'aide' pour voir les commandes ou 'menu' pour les options.";
        }
    }

    // Envoyer un SMS avec la Facade
    private function sendSms($to, $message)
    {
        try {
            $sms = new SMS($to, env('VONAGE_KEY'), $message);
            Vonage::sms()->send($sms);

            Log::info('SMS envoyé', [
                'to' => $to,
                'message' => substr($message, 0, 100) . '...'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS: ' . $e->getMessage());
        }
    }

    // Envoyer SMS manuellement (pour admin)
    public function sendManual(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:160'
        ]);

        $phone = $request->input('phone');
        $message = $request->input('message');

        $this->sendSms($phone, $message);

        return response()->json([
            'status' => 'success',
            'message' => 'SMS envoyé avec succès'
        ]);
    }

    // Webhook pour les accusés de réception
    public function deliveryReceipt(Request $request)
    {
        $messageId = $request->input('messageId');
        $status = $request->input('status');
        $timestamp = $request->input('timestamp');

        Log::info('Accusé de réception SMS', [
            'message_id' => $messageId,
            'status' => $status,
            'timestamp' => $timestamp
        ]);

        return response('OK', 200);
    }
}
