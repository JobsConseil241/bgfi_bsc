<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use GuzzleHttp\Client as GuzzleClient;

class WhatsappController extends Controller
{
    protected $client;
    protected $httpClient;

    public function __construct()
    {
        // Initialisation du client Vonage avec authentification Basic
        $this->client = new Client(
            new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'))
        );

        // Client HTTP pour accès direct à l'API Sandbox si nécessaire
        $this->httpClient = new GuzzleClient([
            'base_uri' => 'https://messages-sandbox.nexmo.com/v1/',
            'auth' => [env('VONAGE_KEY'), env('VONAGE_SECRET')],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }
    /**
     * Traitement des messages WhatsApp entrants
     */
    public function handleInbound(Request $request)
    {
        // Log de toutes les données reçues
        Log::info('Message WhatsApp reçu', ['data' => $request->all()]);

        try {
            // Les données peuvent être structurées différemment dans l'environnement sandbox
            $data = $request->all();

            // Extraction des informations de base
            $from = $data['from'] ?? null;
            $text = $data['text'] ?? null;

            if (!$from || !$text) {
                Log::error('Données de message incomplètes', ['data' => $data]);
                return response()->json(['status' => 'error', 'message' => 'Données incomplètes']);
            }

            Log::info("Message WhatsApp reçu de {$from}: {$text}");

            // Traitement du message et génération d'une réponse
            $response = $this->processMessage($text);

            // Correction : Vérifier la réponse avant d'envoyer des SMS via Twilio
            if ($response == "Entrez le code recu a 4 chiffres") {
                // Vérifier que les variables d'environnement Twilio existent
                if (!env('TWILIO_TOKEN') || !env('TWILIO_SID') || !env('TWILIO_VERIFY_SID')) {
                    Log::error('Variables d\'environnement Twilio manquantes');
                    return response()->json(['status' => 'error', 'message' => 'Configuration Twilio incomplète'], 500);
                }

                try {
                    $authToken = env('TWILIO_TOKEN');
                    $twilioSid = env('TWILIO_SID');
                    $twilioVerifySid = env('TWILIO_VERIFY_SID');

                    // Formatage correct du numéro pour Twilio (format E.164)
                    $formattedPhone = $this->formatPhoneNumberForTwilio($from);
//                    $formattedPhone = "+24107750737";

                    Log::info("Envoi de SMS Twilio au numéro formaté: {$formattedPhone}");

                    $client = new \Twilio\Rest\Client($twilioSid, $authToken);
                    $client->verify->v2->services($twilioVerifySid)
                        ->verifications
                        ->create($formattedPhone, 'sms');

                    // Envoyer la réponse après avoir initié la vérification
                    $this->sendWhatsAppMessage($from, $response);
                    return response()->json(['status' => 'success']);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi du SMS Twilio', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'phone' => $from
                    ]);

                    // Envoyer un message d'erreur à l'utilisateur
                    $this->sendWhatsAppMessage($from, "Désolé, nous ne pouvons pas envoyer de SMS de vérification pour le moment. Veuillez réessayer plus tard.");
                    return response()->json(['status' => 'error', 'message' => 'Erreur d\'envoi SMS'], 500);
                }
            }

            if ($response == "Verification en cours") {
                // Vérifier que les variables d'environnement Twilio existent
                if (!env('TWILIO_TOKEN') || !env('TWILIO_SID') || !env('TWILIO_VERIFY_SID')) {
                    Log::error('Variables d\'environnement Twilio manquantes');
                    return response()->json(['status' => 'error', 'message' => 'Configuration Twilio incomplète'], 500);
                }

                try {
                    $authToken = env('TWILIO_TOKEN');
                    $twilioSid = env('TWILIO_SID');
                    $twilioVerifySid = env('TWILIO_VERIFY_SID');

                    // Vérifier que le texte reçu est bien un code à 4 chiffres
                    if (!$this->verifierLongueurOTP($text)) {
                        $this->sendWhatsAppMessage($from, "Le code doit contenir exactement 4 chiffres. Veuillez réessayer.");
                        return response()->json(['status' => 'error', 'message' => 'Code OTP invalide']);
                    }

                    // Formatage correct du numéro pour Twilio
                    $formattedPhone = $this->formatPhoneNumberForTwilio($from);

                    Log::info("Vérification de SMS Twilio au numéro formaté: {$formattedPhone} avec code: {$text}");

                    $client = new \Twilio\Rest\Client($twilioSid, $authToken);
                    $verification = $client->verify->v2->services($twilioVerifySid)
                        ->verificationChecks
                        ->create([
                            'to' => $formattedPhone,
                            'code' => $text
                        ]);

                    if ($verification->valid) {
                        $response = "Bonjour Monsieur Jeff, votre solde actuel est de 350 000 FCFA. Merci de votre fidelite.";
                        $this->sendWhatsAppMessage($from, $response);
                        return response()->json(['status' => 'success']);
                    } else {
                        // Code invalide, informer l'utilisateur
                        $this->sendWhatsAppMessage($from, "Code incorrect. Veuillez réessayer.");
                        return response()->json(['status' => 'error', 'message' => 'Code invalide']);
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur lors de la vérification Twilio', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'phone' => $from
                    ]);

                    // Envoyer un message d'erreur à l'utilisateur
                    $this->sendWhatsAppMessage($from, "Désolé, nous ne pouvons pas vérifier votre code pour le moment. Veuillez réessayer plus tard.");
                    return response()->json(['status' => 'error', 'message' => 'Erreur de vérification SMS'], 500);
                }
            }

            // Envoi de la réponse par défaut si aucune des conditions précédentes n'est satisfaite
            $this->sendWhatsAppMessage($from, $response);
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du message WhatsApp', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Erreur interne'], 500);
        }
    }

    /**
     * Formatage d'un numéro de téléphone pour Twilio (format E.164)
     * Assure que le numéro commence par '+' et le code pays
     */
    private function formatPhoneNumberForTwilio($phoneNumber) {
        // Nettoyer le numéro (supprimer espaces, tirets, etc.)
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // S'assurer que le numéro commence par un code pays
        // Si le numéro ne commence pas par un '+', ajouter un '+' et le code pays
        // Si vous êtes au Gabon (code pays 241), utilisez ce code
        if (!str_starts_with($phoneNumber, '+')) {
            // Si le numéro commence déjà par le code pays (241)
            if (str_starts_with($cleanNumber, '241')) {
                return '+' . $cleanNumber;
            } else {
                // Sinon, ajouter le code pays
                return '+241' . $cleanNumber;
            }
        }

        return $phoneNumber;
    }

    /**
     * Traitement des notifications de statut
     */
    public function handleStatus(Request $request)
    {
        Log::info('Statut WhatsApp reçu', ['data' => $request->all()]);
        return response()->json(['status' => 'success']);
    }

    /**
     * Logique de traitement des messages
     */
    private function processMessage($text)
    {
        // Normalisez le texte (suppression des espaces en début/fin et conversion en minuscules)
        $text = trim(strtolower($text));

        // Réponses basées sur des mots-clés simples
        if (str_contains($text, 'bonjour') || str_contains($text, 'salut')) {
            return "Bonjour ! Comment puis-je vous aider aujourd'hui ? entrez 'menu' pour en savoir plus.";
        } elseif (str_contains($text, 'horaire')) {
            return "Nous sommes ouverts du lundi au vendredi de 9h à 18h.";
        } elseif (str_contains($text, 'prix') || str_contains($text, 'tarif')) {
            return "Nos tarifs débutent à 50€. Visitez notre site web pour plus d'informations.";
        } elseif (str_contains($text, 'menu') || str_contains($text, 'options')) {
            return "Voici nos options :\n- Horaires\n- Tarifs\n- Contact\n- Solde\nQue souhaitez-vous savoir ?";
        } elseif (str_contains($text, 'contact')) {
            return "Vous pouvez nous contacter au 01 23 45 67 89 ou par email à contact@example.com.";
        } elseif (str_contains($text, 'solde')) {
            return "Entrez le numero de votre compte a 10 chiffres";
        } elseif ($this->validateAccountNumber($text)) {
            return "Entrez le code recu a 4 chiffres";
        } elseif ($this->verifierLongueurOTP($text)) {
            return "Verification en cours";
        } else {
            return "Je n'ai pas compris votre demande. Vous pouvez demander nos horaires, nos tarifs, ou taper 'menu' pour voir toutes les options.";
        }
    }

    /**
     * Vérification de la longueur et du format du code OTP
     */
    function verifierLongueurOTP($codeOTP, $longueurRequise = 4) {
        // Nettoyer le code OTP (supprimer les espaces)
        $codeOTP = trim($codeOTP);

        // Vérifier si le code OTP est numérique
        if (!ctype_digit($codeOTP)) {
            return false;
        }

        // Vérifier la longueur du code OTP
        if (strlen($codeOTP) !== $longueurRequise) {
            return false;
        }

        // Si toutes les vérifications sont passées, la longueur est correcte
        return true;
    }

    /**
     * Validation du numéro de compte (10 chiffres)
     */
    public function validateAccountNumber($accountNumber) {
        // Remove any whitespace or special characters
        $cleanAccountNumber = preg_replace('/[^0-9]/', '', $accountNumber);

        // Vérifier si le numéro de compte contient uniquement des chiffres
        if (!ctype_digit($cleanAccountNumber)) {
            return false;
        }

        // Vérifier que la longueur est exactement 10 chiffres
        if (strlen($cleanAccountNumber) !== 10) {
            return false;
        }

        // La validation est réussie
        return true;
    }

    /**
     * Envoi d'un message WhatsApp en utilisant l'API Messages Sandbox
     */
    public function sendWhatsAppMessage($to, $text)
    {
        try {
            // Vérifier que les variables d'environnement nécessaires existent
            if (!env('VONAGE_NUMBER_WHA')) {
                Log::error('Variable d\'environnement VONAGE_NUMBER_WHA manquante');
                return false;
            }

            // Payload pour l'API Messages Sandbox
            $payload = [
                'from' => env('VONAGE_NUMBER_WHA'),
                'to' => $to,
                'message_type' => 'text',
                'text' => $text,
                'channel' => 'whatsapp'
            ];

            // Utilisation de Guzzle pour faire l'appel API directement
            $response = $this->httpClient->request('POST', 'messages', [
                'json' => $payload
            ]);

            $responseBody = json_decode($response->getBody(), true);

            Log::info('Message WhatsApp envoyé', [
                'to' => $to,
                'response' => $responseBody
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du message WhatsApp', [
                'to' => $to,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Endpoint pour envoyer un message de test
     */
    public function sendTestMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        $phone = $request->input('phone');
        $message = $request->input('message');

        $result = $this->sendWhatsAppMessage($phone, $message);

        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Message envoyé']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Échec de l\'envoi'], 500);
        }
    }
}
