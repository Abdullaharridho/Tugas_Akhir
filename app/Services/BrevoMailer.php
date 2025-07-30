<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoMailer
{
    public static function send($to, $subject, $htmlContent)
    {
        $response = Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => 'Kode OTP Reset Password',
                'email' => 'abdullaharridho03@gmail.com', // ← ganti dengan email yang kamu verifikasi di Brevo
            ],
            'to' => [
                ['email' => $to],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        return $response->json();
    }
}
