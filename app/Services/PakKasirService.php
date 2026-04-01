<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakKasirService
{
    protected string $apiKey;
    protected string $project;
    protected string $baseUrl = 'https://app.pakasir.com/api';

    public function __construct()
    {
        $this->apiKey = config('services.pakkasir.key', env('PAKASIR_API_KEY', ''));
        $this->project = config('services.pakkasir.project', env('PAKASIR_SLUG', ''));
    }

    /**
     * Create QRIS transaction via Pak Kasir API.
     *
     * API Response format:
     * {
     *   "payment": {
     *     "project": "slug",
     *     "order_id": "INV123",
     *     "amount": 99000,
     *     "fee": 1003,
     *     "total_payment": 100003,
     *     "payment_method": "qris",
     *     "payment_number": "00020101021226...", // QR string
     *     "expired_at": "2025-09-19T01:18:49Z"
     *   }
     * }
     */
    public function createTransaction(string $orderId, float $amount): ?array
    {
        if (empty($this->apiKey) || empty($this->project)) {
            Log::warning('PakKasir: API key or project slug not configured. Using DEMO data.');
            $qrString = 'BARBERKU-DEMO-' . $orderId;
            return [
                'payment_number' => $qrString,
                'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString),
                'amount' => $amount,
                'total_payment' => $amount,
                'fee' => 0,
                'expired_at' => now()->addMinutes(15)->toIso8601String(),
            ];
        }

        try {
            $payload = [
                'project' => $this->project,
                'order_id' => $orderId,
                'amount' => (int) $amount,
                'api_key' => $this->apiKey,
            ];

            Log::info('PakKasir: Sending request', $payload);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/transactioncreate/qris", $payload);

            Log::info('PakKasir: Raw response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $json = $response->json();
                $payment = $json['payment'] ?? null;

                if (!$payment || empty($payment['payment_number'])) {
                    Log::error('PakKasir: Missing payment_number in response', $json);
                    return null;
                }

                // Convert QR string to image URL using a free QR API
                $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($payment['payment_number']);

                return [
                    'payment_number' => $payment['payment_number'],
                    'qr_image_url' => $qrImageUrl,
                    'amount' => $payment['amount'] ?? $amount,
                    'total_payment' => $payment['total_payment'] ?? $amount,
                    'fee' => $payment['fee'] ?? 0,
                    'expired_at' => $payment['expired_at'] ?? null,
                ];
            }

            Log::error('PakKasir: API error ' . $response->status() . ': ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('PakKasir Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction detail from Pak Kasir API.
     *
     * GET https://app.pakasir.com/api/transactiondetail?project={slug}&amount={amount}&order_id={order_id}&api_key={api_key}
     *
     * Response: { "transaction": { "amount": 22000, "status": "completed", ... } }
     */
    public function getTransactionDetail(string $orderId, float $amount): ?array
    {
        try {
            $params = [
                'project' => $this->project,
                'amount' => (int) $amount,
                'order_id' => $orderId,
                'api_key' => $this->apiKey,
            ];

            Log::info('PakKasir: Checking transaction detail', $params);

            $response = Http::get("{$this->baseUrl}/transactiondetail", $params);

            Log::info('PakKasir: Detail response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $json = $response->json();
                return $json['transaction'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PakKasir Detail Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simulate payment (Sandbox only).
     */
    public function simulatePayment(string $orderId, float $amount): ?array
    {
        try {
            $response = Http::post("{$this->baseUrl}/paymentsimulation", [
                'project' => $this->project,
                'order_id' => $orderId,
                'amount' => (int) $amount,
                'api_key' => $this->apiKey,
            ]);

            Log::info('PakKasir Simulation response', ['body' => $response->body()]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('PakKasir Simulation Exception: ' . $e->getMessage());
            return null;
        }
    }
}

