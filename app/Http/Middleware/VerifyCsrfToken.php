<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'sms/receive',
        'sms/receive/whatsapp',
        '/webhook/whapi/messages',
        '/webhook/whapi/chats',
        '/webhook/whapi',
        'sms/receive/whatsapp/status',
        'email/capture-email',
        'otp/*',
        'send-airtel-sms',
        'save-feedback/*',
    ];
}
