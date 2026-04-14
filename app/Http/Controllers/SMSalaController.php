<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class SMSalaController extends Controller
{
    public function index(){
        
        $response = Http::get('https://api2.smsala.com/SendSmsV2', [
            'apiToken'          => env('SMSALA_TOKEN'),
            'messageType'       => 3,
            'messageEncoding'   => 1,
            'destinationAddress'=> '24160353951',
            'sourceAddress'     => env('SMSALA_SENDER_ID'),
            'messageText'       => 'Bonjour votre code de verification est 1234',
            'callBackUrl'       => 'https://d6cd22a4113f.ngrok-free.app/smsala/status',
            'userReferenceId'   => 'nzwBePCGzXCC6G9rjD31t9kgUIAQ4GRg',
        ]);
        
        // Vérifier la réponse
        if ($response->successful()) {
            // succès
            Log::info($response->json());
            return 'OK Good';
        } else {
            // erreur
            Log::info($response->body());
            return 'NO Bad';
        }
    }


    public function index_message(){
        
        $response = Http::get('https://api2.smsala.com/SendSmsV2', [
            'apiToken'          => env('SMSALA_TOKEN'),
            'messageType'       => 2,
            'messageEncoding'   => 1,
            'destinationAddress'=> '24105886984',
            'sourceAddress'     => env('SMSALA_SENDER_ID'),
            'messageText'       => 'Bonjour Bienvenue sur SMSALA ceci est un message Test',
            'callBackUrl'       => 'https://d6cd22a4113f.ngrok-free.app/smsala/status',
            'userReferenceId'   => 'nzwBePCGzXCC6G9rjD31t9kgUIAQ4GRg',
        ]);
        
        // Vérifier la réponse
        if ($response->successful()) {
            // succès
            Log::info($response->json());
            return 'OK Message Transactionnel Good';
        } else {
            // erreur
            Log::info($response->body());
            return 'NO Message Transactionnel  Bad';
        }
    }

    public function status(Request $request){
        Log::info($request);
        Log::info('Je suis un status');
    }
}
