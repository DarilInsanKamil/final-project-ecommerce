<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $url = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', env('FONNTE_TOKEN', ''));
    }

    /**
     * Send WhatsApp message via Fonnte
     *
     * @param string $target WhatsApp number
     * @param string $message Message content
     * @return bool
     */
    public function sendMessage(string $target, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('Fonnte token not configured. Skipping WhatsApp message.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url, [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default target country code
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Fonnte API error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}
