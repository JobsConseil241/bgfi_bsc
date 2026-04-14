<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    private OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Envoie un code OTP par SMS via Airtel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'regex:/^(\+?241|0)?[0-9]{8,9}$/'],
            'message' => ['nullable', 'string', 'max:500']
        ], [
            'phone.required' => 'Le numéro de téléphone est requis',
            'phone.regex' => 'Le numéro de téléphone doit être au format gabonais (ex: 24177750737 ou 077750737)'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->input('phone');
        $customMessage = $request->input('message');

        Log::info('Demande d\'envoi OTP', [
            'phone' => $phone,
            'has_custom_message' => !empty($customMessage)
        ]);

        $result = $this->otpService->sendOtp($phone, $customMessage);

        if ($result['success']) {
            // En production, ne pas retourner l'OTP dans la réponse
            // Retirer 'otp' du résultat avant de retourner
            unset($result['otp']);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'expires_in_minutes' => $result['expires_in_minutes'] ?? 10
                ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'error' => $result['error'] ?? null
        ], 500);
    }

    /**
     * Vérifie un code OTP
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'regex:/^(\+?241|0)?[0-9]{8,9}$/'],
            'code' => ['required', 'string', 'regex:/^[0-9]{4,8}$/']
        ], [
            'phone.required' => 'Le numéro de téléphone est requis',
            'phone.regex' => 'Le numéro de téléphone doit être au format gabonais',
            'code.required' => 'Le code OTP est requis',
            'code.regex' => 'Le code OTP doit contenir entre 4 et 8 chiffres'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->input('phone');
        $code = $request->input('code');

        Log::info('Tentative de vérification OTP', [
            'phone' => $phone,
            'code_length' => strlen($code)
        ]);

        $result = $this->otpService->verifyOtp($phone, $code);

        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'verified' => true,
                    'attempts_remaining' => $result['attempts_remaining']
                ]
            ], 200);
        }
        
        throw new \Exception($result['message'] ?? 'Code OTP invalide');
    }

    /**
     * Vérifie si un OTP valide existe pour un numéro
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'regex:/^(\+?241|0)?[0-9]{8,9}$/']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro de téléphone invalide',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->input('phone');
        $hasOtp = $this->otpService->hasValidOtp($phone);

        return response()->json([
            'success' => true,
            'data' => [
                'has_valid_otp' => $hasOtp
            ]
        ], 200);
    }

    /**
     * Invalide un OTP existant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function invalidate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'regex:/^(\+?241|0)?[0-9]{8,9}$/']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro de téléphone invalide',
                'errors' => $validator->errors()
            ], 422);
        }

        $phone = $request->input('phone');
        $invalidated = $this->otpService->invalidateOtp($phone);

        return response()->json([
            'success' => $invalidated,
            'message' => $invalidated ? 'OTP invalidé avec succès' : 'Aucun OTP à invalider'
        ], $invalidated ? 200 : 404);
    }
}

