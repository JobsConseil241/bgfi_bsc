<?php

namespace App\Http\Controllers;

use App\helpers\SMSBulk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;

use Vonage\Client as VonageClient;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class SendSMSController extends Controller
{

    /**
     * Sends an SMS message using Twilio.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSMS()
    {
        // Récupération des identifiants Twilio à partir des variables d'environnement
        $accountSid = env('TWILIO_SID');

        $authToken = env('TWILIO_TOKEN');

        $twilioNumber = '+12542695467';
        $twilio_number = "+12542695467";

        // Initialisation du client Twilio
        $client = new Client($accountSid, $authToken);

        // Envoi du SMS
        $sms = $client->messages->create(
            '+24176546985', // Numéro du destinataire
            [
                'from' => $twilio_number,
                'body' => 'Bonjour Jeff, votre solde actuel est de 350 000 FCFA. Merci d’être un client fidèle.',
            ]
        );

        Log::info($sms);

        return response()->json(['message' => __('SMS sent successfully!')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSMS2(Request $request){
        $request->validate([
            'phone' => 'required|string',
        ]);

        $result = SMSBulk::send(
            $request->phone,
            "Bonjour Jeff, votre solde actuel est de 350 000 FCFA. Merci d’être un client fidèle.",
            $request->input('sender', null)
        );

        $results = SMSBulk::send("+24176546985", "Message de test", "+24177750737");

        Log::info($results);

        Log::info($result);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'SMS envoyé avec succès',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Échec de l\'envoi du SMS',
            'error' => $result['error'] ?? 'Une erreur est survenue'
        ], $result['status'] ?? 500);
    }

    public function sendSMS3(Request $request){
        try {
            $response = Http::withHeaders([
                'Authorization' => 'App a692f351f212ec6ee57bbbe37220d58c-9f69c00b-bd72-4928-884b-da5f2912ecd9',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://51z24z.api.infobip.com/sms/2/text/advanced', [
                'messages' => [
                    [
                        'destinations' => [
                            ['to' => '24177750737']
                        ],
                        'from' => $request->input('from', '447491163443'),
                        'text' => $request->input('message', 'Congratulations on sending your first message. Go ahead and check the delivery report in the next step.')
                    ]
                ]
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            Log::error('Infobip SMS Error: ' . $response->body());
            return response()->json([
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Infobip SMS Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendSMS4(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'App a692f351f212ec6ee57bbbe37220d58c-9f69c00b-bd72-4928-884b-da5f2912ecd9',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->withOptions([
                'follow_redirects' => true
            ])->post('https://51z24z.api.infobip.com/sms/2/text/advanced', [
                'messages' => [
                    [
                        'destinations' => [
                            ['to' => '24177750737']
                        ],
                        'from' => '447491163443',
                        'text' => 'Congratulations on sending your first message. Go ahead and check the delivery report in the next step.'
                    ]
                ]
            ]);

            if ($response->status() == 200) {
                return $response->body();
            } else {
                return 'Unexpected HTTP status: ' . $response->status() . ' ' . $response->reason();
            }
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return 'Error: ' . $e->getMessage();
        }
    }

    public function receiveMessage(Request $request)
    {

        Log::info('Requête SMS reçue', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        // Récupérer les informations du SMS
        $from = $request->input('msisdn');
        $text = $request->input('text');

        Log::info("SMS reçu de $from : $text");

        // Logique simple du chatbot
        $response = $this->getResponse($text);

        // Envoyer la réponse
        $this->sendSms6($from, $response);

        return response()->json(['status' => 'success']);
    }

    public function receiveMessageWhatsapp(Request $request)
    {

        Log::info('Requête SMS reçue', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        // Récupérer les informations du SMS
        $from = $request->input('msisdn');
        $text = $request->input('text');

        Log::info("SMS reçu de $from : $text");

        // Logique simple du chatbot
        $response = $this->getResponse($text);

        // Envoyer la réponse
        $this->sendSms6($from, $response);

        return response()->json(['status' => 'success']);
    }

    private function getResponse($text)
    {
        $text = strtolower($text);

        // Réponses basées sur des mots-clés simples
        if (str_contains($text, 'bonjour') || str_contains($text, 'salut')) {
            return "Bonjour ! Comment puis-je vous aider ?";
        } elseif (str_contains($text, 'horaire')) {
            return "Nous sommes ouverts du lundi au vendredi de 9h à 18h.";
        } elseif (str_contains($text, 'prix') || str_contains($text, 'tarif')) {
            return "Nos tarifs débutent à 50€. Visitez notre site web pour plus d'informations.";
        } elseif (str_contains($text, 'contact') || str_contains($text, 'téléphone')) {
            return "Vous pouvez nous joindre au 01 23 45 67 89.";
        } else {
            return "Merci pour votre message. Un conseiller vous contactera bientôt.";
        }
    }

    private function sendSms6($to, $message)
    {
        try {

            $credentials = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
            $client = new VonageClient($credentials);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS(
                    $to,
                    env('VONAGE_SMS_FROM'),
                    $message
                )
            );

            Log::info("Tentative d'envoi de SMS", [
                'to' => $to,
                'from' => env('VONAGE_SMS_FROM'),
                'message' => $message,
                'response' => json_encode($response)
            ]);

            Log::info("SMS envoyé à $to", ['response' => $response]);

        } catch (\Exception $e) {
            Log::error("Erreur d'envoi de SMS: " . $e->getMessage());
        }
    }

    private function sendSmsWha($to, $message)
    {
        try {

            $credentials = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
            $client = new VonageClient($credentials);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS(
                    $to,
                    env('VONAGE_SMS_FROM'),
                    $message,
                    "text",
                )
            );

            Log::info("SMS envoyé à $to", ['response' => $response]);

        } catch (\Exception $e) {
            Log::error("Erreur d'envoi de SMS: " . $e->getMessage());
        }
    }

    public function sendSMS5(Request $request) {

        $result = $this->sendVonageSms($request->phone, "Bonjour Monsieur/Madame ". $request->nom ." votre solde actuel est de ". number_format( $request->solde, 0, ',', ' '). " FCFA. \nMerci de votre fidelite.");
//        $result1 = $this->sendVonageSms('+241077597658', 'Bonjour Monsieur Herve, votre solde actuel est de 350 000 FCFA. Merci de votre fidelite.');
//        $result2 = $this->sendVonageSms('+241077750737', 'Bonjour Monsieur Jeff, votre solde actuel est de 350 000 FCFA. Merci de votre fidelite.');
        

        if (!empty($result['success'])) {
            return response()->json([
                'success' => true,
                'error' => $result['error'] ?? null,
                'status' => $result['status'] ?? 200,
            ], $result['status'] ?? 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Une erreur est survenue.',
            ], $result['status'] ?? 500);
        }
    }

    public function sendVonageSmsTwo($phone, $from, $text){

    }

    public function sendVonageSms($to, $message)
    {
        // Import des classes nécessaires en haut de votre fichier de contrôleur :

        try {
            // Initialisation du client Vonage
            $credentials = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
            $vonageApi = new VonageClient($credentials);

            // Envoi du SMS
            $from = env('VONAGE_SMS_FROM', 'Laravel');
            $response = $vonageApi->sms()->send(
                new SMS($to, $from, $message)
            );

            $messageResult = $response->current();

            if ($messageResult->getStatus() == 0) {
                return [
                    'success' => true,
                    'message' => 'SMS envoyé avec succès',
                    'message_id' => $messageResult->getMessageId()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Échec de l\'envoi',
                    'error' => $messageResult->getStatus()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du SMS',
                'error' => $e->getMessage()
            ];
        }
    }



    /**
     * Sends an OTP code to a provided phone number via SMS.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOTPVerification(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[1-9]\d{1,14}$/'],
        ]);

        $authToken = env('TWILIO_TOKEN');
        $twilioSid = env('TWILIO_SID');
        $twilioVerifySid = env('TWILIO_VERIFY_SID');


        $client = new Client($twilioSid, $authToken);
        $client->verify->v2->services($twilioVerifySid)
            ->verifications
            ->create($validatedData['phone'], 'sms');

        return response()->json(['message' => __('OTP sent successfully!')]);
    }

    /**
     * Verifies the OTP code sent to a phone number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOTP(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'code' => ['required', 'string'],
        ]);

        $authToken = env('TWILIO_TOKEN');
        $twilioSid = env('TWILIO_SID');
        $twilioVerifySid = env('TWILIO_VERIFY_SID');

        $client = new Client($twilioSid, $authToken);
        $verification = $client->verify->v2->services($twilioVerifySid)
            ->verificationChecks
            ->create([
                'to' => $validatedData['phone'],
                'code' => $validatedData['code']
            ]);

        if ($verification->valid) {
            return response()->json(['message' => __('OTP verified successfully!')]);
        }


        return response()->json(['error' => __('Invalid OTP or phone number.')], 400);
    }
}
