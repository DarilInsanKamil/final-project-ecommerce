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

    public function checkStatus(Booking $booking, PakKasirService $pakKasir, FonnteService $fonnte)
    {
        $payment = $booking->payment;

        if (!$payment) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($payment->status === 'paid') {
            return back()->with('success', 'Pembayaran sudah lunas.');
        }

        $detail = $pakKasir->getTransactionDetail($payment->invoice_id, $payment->amount);

        if ($detail && ($detail['status'] === 'completed' || $detail['status'] === 'paid')) {
            $this->markAsPaid($payment, $fonnte);
            return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        return back()->with('info', 'Pembayaran belum diterima. Silakan selesaikan pembayaran Anda.');
    }

    public function webhook(Request $request, PakKasirService $pakKasir, FonnteService $fonnte)
    {
        $payload = $request->all();
        Log::info('Pak Kasir Webhook received', $payload);

        $invoiceId = $request->input('order_id');
        $amount = $request->input('amount');
        
        $payment = Payment::where('invoice_id', $invoiceId)->first();

        if (!$payment) {
            Log::error('Webhook: Payment not found', ['invoice_id' => $invoiceId]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Security: Verify with Transaction Detail API before confirmed
        // This confirms the webhook data is legit
        $detail = $pakKasir->getTransactionDetail($invoiceId, $amount);

        if ($detail && ($detail['status'] === 'completed' || $detail['status'] === 'paid')) {
            $this->markAsPaid($payment, $fonnte);
            return response()->json(['message' => 'Webhook verified and processed']);
        }

        Log::warning('Webhook: Detail verification failed or pending', ['detail' => $detail]);
        return response()->json(['message' => 'Transaction not completed yet or invalid']);
    }

    /**
     * Mark a payment as paid and confirm the booking.
     */
    private function markAsPaid(Payment $payment, FonnteService $fonnte)
    {
        if ($payment->status === 'paid') {
            return;
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $booking = $payment->booking;
        $booking->update(['status' => 'confirmed']);

        // Notify Customer via WhatsApp
        $formattedAmount = number_format($payment->amount, 0, ',', '.');
        $message = "Halo *{$booking->customer_name}*,\n\n" .
                   "Pembayaran Anda sebesar *Rp {$formattedAmount}* telah kami terima dan dikonfirmasi.\n\n" .
                   "Detail Booking:\n" .
                   "- Layanan: {$booking->service->name}\n" .
                   "- Tanggal: {$booking->booking_date}\n" .
                   "- Jam: {$booking->booking_time}\n\n" .
                   "Sampai jumpa di BarberKu! 💈";

        $fonnte->sendMessage($booking->customer_phone, $message);
    }
}
