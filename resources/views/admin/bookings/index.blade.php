@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-zinc-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold text-white">All Bookings</h2>
        
        <form action="{{ route('admin.bookings') }}" method="GET" class="flex flex-wrap gap-2 w-full md:w-auto items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name/phone..." class="bg-zinc-950 border border-zinc-800 text-sm text-zinc-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
            <input type="date" name="date" value="{{ request('date') }}" class="bg-zinc-950 border border-zinc-800 text-sm text-zinc-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
            <select name="status" class="bg-zinc-950 border border-zinc-800 text-sm text-zinc-300 rounded-lg px-3 py-2 outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class='bx bx-filter-alt'></i> Filter
            </button>
            @if(request()->hasAny(['search', 'date', 'status']))
                <a href="{{ route('admin.bookings') }}" class="text-zinc-500 hover:text-white text-sm px-2">Clear</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-zinc-950/50 text-zinc-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">ID / Customer</th>
                    <th class="px-6 py-4 font-medium">Service Info</th>
                    <th class="px-6 py-4 font-medium">Barber</th>
                    <th class="px-6 py-4 font-medium">Date & Time</th>
                    <th class="px-6 py-4 font-medium text-center">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50 text-sm">
                @forelse($bookings as $booking)
                <tr class="hover:bg-zinc-800/20 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-amber-500 mb-1">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-white font-medium">{{ $booking->customer_name }}</div>
                        <div class="text-zinc-500 text-xs">{{ $booking->customer_phone }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-zinc-300 font-medium">{{ $booking->service->name ?? 'Deleted Service' }}</div>
                        <div class="text-zinc-500 text-xs mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-user text-zinc-500'></i>
                            <span class="text-zinc-300">{{ $booking->staff->name ?? 'Any Barber' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-zinc-200">{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, M d Y') }}</div>
                        <div class="text-zinc-400 font-medium mt-1"><i class='bx bx-time-five'></i> {{ substr($booking->booking_time, 0, 5) }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.bookings.status', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-xs font-semibold px-2 py-1 rounded-md bg-zinc-950 border {{ $booking->status == 'confirmed' || $booking->status == 'paid' ? 'border-green-500/30 text-green-400' : ($booking->status == 'done' ? 'border-blue-500/30 text-blue-400' : 'border-amber-500/30 text-amber-400') }} outline-none cursor-pointer">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                        
                        @if($booking->payment)
                            <div class="mt-2 text-[10px] text-zinc-500 uppercase">
                                Payment: {{ $booking->payment->status }}
                            </div>
                        @else
                            <div class="mt-2 text-[10px] text-zinc-500 uppercase">
                                No Payment
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="w-8 h-8 rounded bg-zinc-800 hover:bg-amber-500 hover:text-zinc-900 text-zinc-400 transition-colors inline-flex items-center justify-center">
                            <i class='bx bx-info-circle'></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                        <div class="text-4xl mb-3"><i class='bx bx-calendar-x'></i></div>
                        <p>No bookings found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
