@extends('layouts.app')

@section('title', 'Payment - BarberKu')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Complete Your Payment</h1>
        <p class="text-zinc-400">Scan the QRIS code below to confirm your booking.</p>
    </div>

    <div class="card p-8 text-center relative overflow-hidden bg-zinc-900 border border-zinc-700 rounded-2xl">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-zinc-300">Total Amount</h3>
            <div class="text-4xl font-bold text-amber-500 mt-2">Rp {{ number_format($transaction['total_payment'] ?? $transaction['amount'], 0, ',', '.') }}</div>
            @if(isset($transaction['fee']) && $transaction['fee'] > 0)
                <p class="text-sm text-zinc-500 mt-1">
                    Subtotal: Rp {{ number_format($transaction['amount'], 0, ',', '.') }} + Fee: Rp {{ number_format($transaction['fee'], 0, ',', '.') }}
                </p>
            @endif
            <p class="text-sm text-zinc-500 mt-1">Order ID: {{ $payment->invoice_id }}</p>
        </div>

        <div class="flex justify-center mb-8">
            <div class="bg-white p-4 rounded-xl inline-block shadow-lg">
                {{-- QR code will be rendered here by JavaScript --}}
                <div id="qrcode" class="w-64 h-64 flex items-center justify-center"></div>
            </div>
        </div>

        <div class="bg-zinc-800/50 p-4 rounded-xl border border-zinc-700/50 text-left mb-6">
            <h4 class="font-medium text-white mb-2 flex items-center gap-2"><i class='bx bx-info-circle text-amber-500'></i> Payment Instructions</h4>
            <ol class="list-decimal list-inside text-sm text-zinc-400 space-y-2">
                <li>Open your preferred e-wallet or mobile banking app.</li>
                <li>Tap the <strong>Scan QR</strong> or <strong>Pay</strong> button.</li>
                <li>Scan the QRIS code displayed above.</li>
                <li>Verify the payment details and amount.</li>
                <li>Enter your PIN to complete the transaction.</li>
            </ol>
        </div>

        <div class="mb-4">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-500/20 border border-green-500/50 text-green-400 text-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="p-4 rounded-xl bg-blue-500/20 border border-blue-500/50 text-blue-400 text-sm mb-4">
                    {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-500/20 border border-red-500/50 text-red-400 text-sm mb-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('payment.check', $booking->id) }}" class="btn-secondary flex-1 flex items-center justify-center gap-2">
                <i class='bx bx-refresh'></i> Check Status
            </a>
            <a href="{{ route('user.profile') }}" class="btn-primary flex-1 flex items-center justify-center gap-2">
                <i class='bx bx-list-ul'></i> My Bookings
            </a>
        </div>
        
        @if($payment->status === 'paid')
            <div class="absolute inset-0 bg-zinc-900/90 backdrop-blur-sm flex flex-col justify-center items-center z-10 transition-all">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mb-4">
                    <i class='bx bx-check text-4xl text-white'></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Payment Successful!</h2>
                <p class="text-zinc-400 mb-6 text-center max-w-xs">Your appointment is confirmed. We have sent the details to your WhatsApp.</p>
                <a href="{{ route('home') }}" class="btn-primary">Return Home</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var paymentNumber = @json($transaction['payment_number'] ?? '');
        if (paymentNumber) {
            var qr = qrcode(0, 'M');
            qr.addData(paymentNumber);
            qr.make();
            document.getElementById('qrcode').innerHTML = qr.createImgTag(4, 16);
        } else {
            document.getElementById('qrcode').innerHTML = '<p class="text-red-500 text-sm">QR Code tidak tersedia.</p>';
        }
    });
</script>
@endpush
