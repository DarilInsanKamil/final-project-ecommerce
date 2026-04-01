@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-zinc-400 font-medium text-sm">Total Bookings</h3>
            <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center">
                <i class='bx bx-calendar text-xl'></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ number_format($totalBookings) }}</div>
    </div>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-zinc-400 font-medium text-sm">Total Revenue</h3>
            <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center">
                <i class='bx bx-wallet text-xl'></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
    </div>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-zinc-400 font-medium text-sm">Active Staff</h3>
            <div class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center">
                <i class='bx bx-user text-xl'></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $activeStaff }}</div>
    </div>
</div>

<div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
    <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
        <h3 class="text-lg font-semibold text-white">Recent Real-time Bookings</h3>
        <a href="{{ route('admin.bookings') }}" class="text-sm font-medium text-amber-500 hover:text-amber-400">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-950/50 text-zinc-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Customer</th>
                    <th class="px-6 py-4 font-medium">Service</th>
                    <th class="px-6 py-4 font-medium">Date & Time</th>
                    <th class="px-6 py-4 font-medium">Barber</th>
                    <th class="px-6 py-4 font-medium text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse($recentBookings as $b)
                <tr class="hover:bg-zinc-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-white">{{ $b->customer_name }}</div>
                        <div class="text-xs text-zinc-500">{{ $b->customer_phone }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-zinc-300">{{ $b->service->name ?? 'N/A' }}</div>
                        <div class="text-xs text-zinc-500">Rp {{ number_format($b->total_price, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-zinc-300">{{ \Carbon\Carbon::parse($b->booking_date)->format('M d, Y') }}</div>
                        <div class="text-xs text-zinc-500">{{ substr($b->booking_time, 0, 5) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-zinc-300">{{ $b->staff->name ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($b->status == 'paid' || $b->status == 'confirmed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Confirmed</span>
                        @elseif($b->status == 'pending' || $b->status == 'unpaid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-500/10 text-zinc-400 border border-zinc-500/20">{{ ucfirst($b->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                        No recent bookings found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
