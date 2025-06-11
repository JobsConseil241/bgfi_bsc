<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InboundEmailController extends Controller
{
    private $smsGatewayUrl = 'http://192.168.1.81/cgi/WebCGI';
    private $smsAccount = 'apiuser';
    private $smsPassword = 'apipass';

    public function handleInboundSMS(Request $request)
    {
        try {
            // Extraire les données
            $subject = $request->input('Subject');
            $textBody = $request->input('TextBody');

            // Extraire le numéro de téléphone et le port
            preg_match('/SMS from (\+\d+) on TG100\'s port (\d+)/', $subject, $matches);
            $phoneNumber = $matches[1] ?? null;
            $port = $matches[2] ?? 1;

            if (!$phoneNumber) {
                Log::error('Impossible d\'extraire le numéro de téléphone', ['subject' => $subject]);
                return response()->json(['error' => 'Numéro invalide'], 400);
            }

            // Traitement automatique du message
            $this->processMessage($phoneNumber, $textBody, $port);

            Log::info('SMS reçu et traité', [
                'phone' => $phoneNumber,
                'message' => $textBody,
                'port' => $port
            ]);

            return response()->json([
                'success' => true,
                'phone' => $phoneNumber,
                'message' => $textBody
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement SMS', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    private function processMessage($phoneNumber, $message, $port = 1)
    {
        $message = strtolower(trim($message));

        // Réponses automatiques
        switch ($message) {
            case 'bonjour':
            case 'hello':
            case 'salut':
                $this->sendSMS($phoneNumber, 'Bonjour ! Comment puis-je vous aider ? POSITION pour le solde de votre compte ', $port);
                break;

            case 'stop':
            case 'arret':
                $this->sendSMS($phoneNumber, 'Vous avez été désinscrit avec succès.', $port);
                break;

            case 'position':
            case 'positio':
                $this->sendSMS($phoneNumber, 'Entrez le numero de votre compte (12 chiffres).', $port);
                break;

            case 'help':
            case 'aide':
                $this->sendSMS($phoneNumber, 'Commandes disponibles: STOP pour se désinscrire, INFO pour plus d\'informations.', $port);
                break;

            case 'info':
                $this->sendSMS($phoneNumber, 'Service SMS - Contactez-nous pour plus d\'informations.', $port);
                break;

            default:
                // Réponse automatique pour accusé de réception
                $this->sendSMS($phoneNumber, 'Message reçu. Nous vous répondrons bientôt.', $port);

                // Log pour les admins
                Log::info('Message nécessitant une réponse manuelle', [
                    'phone' => $phoneNumber,
                    'message' => $message,
                    'port' => $port
                ]);
                break;
        }
    }

    private function sendSMS($phoneNumber, $message, $port = 1)
    {
        try {
            // Nettoyer le numéro (enlever le + si présent)
            $cleanNumber = ltrim($phoneNumber, '+');

            // Paramètres pour l'API
            $params = [
                '1500101' => '',
                'account' => $this->smsAccount,
                'password' => $this->smsPassword,
                'port' => $port,
                'destination' => $cleanNumber,
                'content' => $message
            ];

            // Construire l'URL
            $url = $this->smsGatewayUrl . '?' . http_build_query($params);

            // Envoyer la requête
            $response = Http::timeout(30)->get($url);

            $status = $response->successful() ? 'sent' : 'failed';

            Log::info('SMS envoyé', [
                'phone' => $phoneNumber,
                'message' => $message,
                'status' => $status,
                'port' => $port,
                'response' => $response->body()
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS', [
                'phone' => $phoneNumber,
                'message' => $message,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
