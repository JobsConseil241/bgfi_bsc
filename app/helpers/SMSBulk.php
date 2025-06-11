<?php

namespace App\helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSBulk
{
    /**
     * Envoyer un SMS via BulkSMS API
     *
     * @param string $to Numéro de téléphone du destinataire
     * @param string $message Contenu du message
     * @param string|null $from Expéditeur (optionnel)
     * @return array Résultat de l'opération
     */
    public static function send(string $to, string $message, ?string $from = null): array
    {
        $token = config('services.bulksms.token');
        $baseUrl = config('services.bulksms.url', 'https://api.bulksms.com/v1');

        try {
            $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $token,
                    'Content-Type' => 'application/json',
                ])
                ->post($baseUrl . '/messages', [
                    'to' => $to,
                    'body' => $message,
                    'from' => $from,
                ]);



            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('BulkSMS Error: ' . $response->body());
            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('BulkSMS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifier le solde du compte
     *
     * @return array Informations sur le solde
     */
    public static function checkBalance(): array
    {
        $token = config('services.bulksms.token');
        $baseUrl = config('services.bulksms.url', 'https://api.bulksms.com/v1');

        try {
            $response = Http::withToken($token)
                ->get($baseUrl . '/credits');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
