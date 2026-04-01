<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PakKasirService;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function process(Booking $booking, PakKasirService $pakKasir)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Booking ini sudah diproses.');
        }

        $payment = $booking->payment()->firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'invoice_id' => 'INV-' . strtoupper(uniqid()),
                'amount' => $booking->total_price,
                'status' => 'unpaid'
            ]
        );

        // If payment_number already stored, display it directly
        if ($payment->payment_url) {
            $transaction = [
                'payment_number' => $payment->payment_url,
                'amount' => $payment->amount,
                'total_payment' => $payment->amount,
                'fee' => 0,
            ];
            return view('bookings.payment', compact('booking', 'payment', 'transaction'));
        }

        $result = $pakKasir->createTransaction($payment->invoice_id, $payment->amount);

        Log::info('PakKasir transaction result:', ['result' => $result]);

        if (!$result || empty($result['payment_number'])) {
            Log::error('PakKasir: Failed to get payment_number');
            return back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }

        // Save payment_number (QR string) to database
        $payment->update(['payment_url' => $result['payment_number']]);

        $transaction = $result;

        return view('bookings.payment', compact('booking', 'payment', 'transaction'));
    }

    public function webhook(Request $request, FonnteService $fonnte)
    {
        $payload = $request->all();
        Log::info('Pak Kasir Webhook received', $payload);

        // Map payload based on actual Pak Kasir structure
        $invoiceId = $request->input('order_id');
        $transactionStatus = $request->input('status'); // Pak Kasir uses "status"

        $payment = Payment::where('invoice_id', $invoiceId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($transactionStatus == 'completed') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $booking = $payment->booking;
            $booking->update(['status' => 'confirmed']);

            // Notify Customer
            $message = "Halo {$booking->customer_name},\nPembayaran as sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " berhasil dikonfirmasi. Sampai jumpa di BarberKu pada {$booking->booking_date} pukul {$booking->booking_time}!";
            $fonnte->sendMessage($booking->customer_phone, $message);
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
