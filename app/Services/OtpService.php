<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    private AirtelSmsService $airtelService;
    private int $otpLength;
    private int $otpExpiryMinutes;
    private int $maxAttempts;

    public function __construct(AirtelSmsService $airtelService)
    {
        $this->airtelService = $airtelService;
        $this->otpLength = config('services.otp.length', 6);
        $this->otpExpiryMinutes = config('services.otp.expiry_minutes', 10);
        $this->maxAttempts = config('services.otp.max_attempts', 3);
    }

    /**
     * Génère un code OTP numérique
     *
     * @param int $length Longueur du code (par défaut 6)
     * @return string Code OTP généré
     */
    public function generateOtp(int $length = null): string
    {
        $length = $length ?? $this->otpLength;
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        
        return (string) rand($min, $max);
    }

    /**
     * Envoie un code OTP par SMS via Airtel
     *
     * @param string $phone Numéro de téléphone
     * @param string|null $customMessage Message personnalisé (optionnel)
     * @return array ['success' => bool, 'otp' => string|null, 'message' => string]
     */
    public function sendOtp(string $phone, ?string $customMessage = null): array
    {
        try {
            // Formater le numéro de téléphone
            $formattedPhone = $this->airtelService->formatPhone($phone);

            // Générer le code OTP
            $otp = $this->generateOtp();

            // Préparer le message
            $message = $customMessage ?? "Votre code de vérification BGFI est : {$otp}. Valide pendant {$this->otpExpiryMinutes} minutes. Ne partagez jamais ce code.";

            // Envoyer le SMS via Airtel
            $smsResult = $this->airtelService->sendSms($formattedPhone, $message);

            if (!$smsResult['success']) {
                Log::error('Échec envoi OTP', [
                    'phone' => $formattedPhone,
                    'error' => $smsResult['error'] ?? 'Erreur inconnue'
                ]);

                return [
                    'success' => false,
                    'otp' => null,
                    'message' => 'Échec de l\'envoi du code OTP',
                    'error' => $smsResult['error'] ?? 'Erreur inconnue'
                ];
            }

            // Stocker le code OTP dans le cache avec expiration
            $cacheKey = $this->getOtpCacheKey($formattedPhone);
            Cache::put($cacheKey, [
                'otp' => $otp,
                'attempts' => 0,
                'created_at' => now()->toDateTimeString()
            ], now()->addMinutes($this->otpExpiryMinutes));

            Log::info('OTP généré et envoyé', [
                'phone' => $formattedPhone,
                'otp_length' => strlen($otp),
                'expires_in_minutes' => $this->otpExpiryMinutes
            ]);

            return [
                'success' => true,
                'otp' => $otp, // En production, ne pas retourner l'OTP dans la réponse
                'message' => 'Code OTP envoyé avec succès',
                'expires_in_minutes' => $this->otpExpiryMinutes
            ];

        } catch (\Exception $e) {
            Log::error('Erreur génération/envoi OTP', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'otp' => null,
                'message' => 'Erreur lors de la génération du code OTP',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifie un code OTP
     *
     * @param string $phone Numéro de téléphone
     * @param string $code Code OTP à vérifier
     * @return array ['valid' => bool, 'message' => string, 'attempts_remaining' => int]
     */
    public function verifyOtp(string $phone, string $code): array
    {
        try {
            // Formater le numéro de téléphone
            $formattedPhone = $this->airtelService->formatPhone($phone);

            // Nettoyer le code OTP (supprimer les espaces)
            $code = trim($code);

            // Vérifier que le code est numérique
            if (!ctype_digit($code)) {
                return [
                    'valid' => false,
                    'message' => 'Le code OTP doit contenir uniquement des chiffres',
                    'attempts_remaining' => $this->getRemainingAttempts($formattedPhone)
                ];
            }

            // Récupérer les données OTP du cache
            $cacheKey = $this->getOtpCacheKey($formattedPhone);
            $otpData = Cache::get($cacheKey);

            if (!$otpData) {
                Log::warning('Tentative de vérification OTP expiré ou inexistant', [
                    'phone' => $formattedPhone
                ]);

                return [
                    'valid' => false,
                    'message' => 'Code OTP expiré ou inexistant. Veuillez demander un nouveau code.',
                    'attempts_remaining' => 0
                ];
            }

            // Vérifier le nombre de tentatives
            $attempts = ($otpData['attempts'] ?? 0) + 1;

            if ($attempts > $this->maxAttempts) {
                // Supprimer le code OTP après trop de tentatives
                Cache::forget($cacheKey);

                Log::warning('Trop de tentatives de vérification OTP', [
                    'phone' => $formattedPhone,
                    'attempts' => $attempts
                ]);

                return [
                    'valid' => false,
                    'message' => 'Trop de tentatives échouées. Veuillez demander un nouveau code OTP.',
                    'attempts_remaining' => 0
                ];
            }

            // Vérifier si le code correspond
            if ($otpData['otp'] === $code) {
                // Supprimer le code OTP après vérification réussie
                Cache::forget($cacheKey);

                Log::info('OTP vérifié avec succès', [
                    'phone' => $formattedPhone
                ]);

                return [
                    'valid' => true,
                    'message' => 'Code OTP vérifié avec succès',
                    'attempts_remaining' => $this->maxAttempts - $attempts
                ];
            }

            // Incrémenter le nombre de tentatives
            $otpData['attempts'] = $attempts;
            Cache::put($cacheKey, $otpData, now()->addMinutes($this->otpExpiryMinutes));

            $remaining = $this->maxAttempts - $attempts;

            Log::info('Code OTP incorrect', [
                'phone' => $formattedPhone,
                'attempts' => $attempts,
                'remaining' => $remaining
            ]);

            return [
                'valid' => false,
                'message' => "Code OTP incorrect. {$remaining} tentative(s) restante(s).",
                'attempts_remaining' => $remaining
            ];

        } catch (\Exception $e) {
            Log::error('Erreur vérification OTP', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'valid' => false,
                'message' => 'Erreur lors de la vérification du code OTP',
                'error' => $e->getMessage(),
                'attempts_remaining' => 0
            ];
        }
    }

    /**
     * Récupère le nombre de tentatives restantes
     *
     * @param string $formattedPhone Numéro de téléphone formaté
     * @return int Nombre de tentatives restantes
     */
    private function getRemainingAttempts(string $formattedPhone): int
    {
        $cacheKey = $this->getOtpCacheKey($formattedPhone);
        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return 0;
        }

        $attempts = $otpData['attempts'] ?? 0;
        return max(0, $this->maxAttempts - $attempts);
    }

    /**
     * Génère la clé de cache pour un OTP
     *
     * @param string $phone Numéro de téléphone formaté
     * @return string Clé de cache
     */
    private function getOtpCacheKey(string $phone): string
    {
        return "otp_{$phone}";
    }

    /**
     * Vérifie si un OTP existe et est valide (sans le vérifier)
     *
     * @param string $phone Numéro de téléphone
     * @return bool True si un OTP valide existe
     */
    public function hasValidOtp(string $phone): bool
    {
        $formattedPhone = $this->airtelService->formatPhone($phone);
        $cacheKey = $this->getOtpCacheKey($formattedPhone);
        
        return Cache::has($cacheKey);
    }

    /**
     * Supprime un OTP du cache
     *
     * @param string $phone Numéro de téléphone
     * @return bool True si supprimé avec succès
     */
    public function invalidateOtp(string $phone): bool
    {
        $formattedPhone = $this->airtelService->formatPhone($phone);
        $cacheKey = $this->getOtpCacheKey($formattedPhone);
        
        return Cache::forget($cacheKey);
    }
}

