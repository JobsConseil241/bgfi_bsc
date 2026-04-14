<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AirtelSmsService
{
    private string $baseUrl;
    private string $username;
    private string $password;
    private string $originAddr;

    public function __construct()
    {
        $this->baseUrl = config('services.airtel.base_url', 'https://messaging.airtel.ga:9002/smshttp/qs/');
        $this->username = config('services.airtel.username', env('AIRTEL_USERNAME'));
        $this->password = config('services.airtel.password', env('AIRTEL_PASSWORD'));
        $this->originAddr = config('services.airtel.origin_addr', env('AIRTEL_ORIGIN_ADDR', 'BGFI'));
    }

    /**
     * Envoie un SMS via l'API Airtel
     *
     * @param string $mobileNo Numéro de téléphone du destinataire (format: 24177750737)
     * @param string $message Message à envoyer
     * @return array ['success' => bool, 'message' => string, 'data' => mixed]
     */
    public function sendSms(string $mobileNo, string $message): array
    {
        try {
            // Nettoyer le numéro de téléphone (supprimer les espaces, +, etc.)
            $mobileNo = preg_replace('/[^0-9]/', '', $mobileNo);

            // Vérifier que le numéro est valide
            if (empty($mobileNo) || strlen($mobileNo) < 9) {
                return [
                    'success' => false,
                    'message' => 'Numéro de téléphone invalide',
                    'error' => 'Le numéro doit contenir au moins 9 chiffres'
                ];
            }

            // Préparer les paramètres de la requête
            $params = [
                'REQUESTTYPE' => 'SMSSubmitReq',
                'MOBILENO' => $mobileNo,
                'USERNAME' => $this->username,
                'PASSWORD' => $this->password,
                'ORIGIN_ADDR' => $this->originAddr,
                'TYPE' => '0',
                'MESSAGE' => $message
            ];

            Log::info('Envoi SMS Airtel', [
                'mobile_no' => $mobileNo,
                'origin_addr' => $this->originAddr,
                'message_length' => strlen($message)
            ]);

            // Envoyer la requête GET avec les paramètres encodés
            $response = Http::connectTimeout(30)
                ->timeout(60)
                ->get($this->baseUrl, $params);

            Log::info('Réponse API Airtel', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseBody = $response->body();
                
                // Vérifier si la réponse indique un succès
                // L'API Airtel peut retourner différents formats de réponse
                // On considère qu'une réponse 200 est un succès
                return [
                    'success' => true,
                    'message' => 'SMS envoyé avec succès',
                    'data' => [
                        'response' => $responseBody,
                        'mobile_no' => $mobileNo
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => 'Échec de l\'envoi du SMS',
                'error' => $response->body(),
                'status_code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS Airtel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du SMS',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Formate un numéro de téléphone pour Airtel
     * Supprime les caractères non numériques et s'assure du bon format
     *
     * @param string $phone Numéro de téléphone
     * @return string Numéro formaté
     */
    public function formatPhone(string $phone): string
    {
        // Supprimer tous les caractères non numériques
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Si le numéro commence par 0, le remplacer par l'indicatif 241
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '241' . substr($cleaned, 1);
        }

        // Si le numéro ne commence pas par 241, l'ajouter
        if (!str_starts_with($cleaned, '241')) {
            $cleaned = '241' . $cleaned;
        }

        return $cleaned;
    }
}

