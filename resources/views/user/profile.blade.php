@extends('layouts.app')

@section('title', 'My Profile - BarberKu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">My Profile</h1>
        <p class="text-zinc-400">Manage your account details and view booking history.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Settings -->
        <div class="lg:col-span-1">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 sticky top-24">
                <h2 class="text-xl font-bold text-white mb-6 border-b border-zinc-800 pb-4">Account Settings</h2>
                
                @if(session('success'))
                    <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-500 p-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Email Address</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full bg-zinc-950/50 border border-zinc-800 text-zinc-500 rounded-xl px-4 py-2.5 outline-none cursor-not-allowed">
                        <p class="text-xs text-zinc-500 mt-1">Email cannot be changed.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
                    </div>
                    
                    <button type="submit" class="w-full btn-primary py-2.5 mt-2">Save Changes</button>
                </form>

                <div class="mt-8 pt-6 border-t border-zinc-800">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 rounded-xl px-4 py-2.5 outline-none transition-colors flex items-center justify-center gap-2 font-medium">
                            <i class='bx bx-log-out'></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div class="lg:col-span-2">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 border-b border-zinc-800 pb-4">Booking History</h2>
                
                <div class="space-y-4">
                    @forelse($bookings as $booking)
                        <div class="border border-zinc-800 bg-zinc-950 rounded-xl p-5 hover:border-zinc-700 transition-colors">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4 pb-4 border-b border-zinc-800/50">
                                <div>
                                    <div class="text-amber-500 font-medium text-sm mb-1">Appointment #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    <h3 class="text-lg font-bold text-white">{{ $booking->service->name ?? 'Service Removed' }}</h3>
                                </div>
                                <div class="text-left sm:text-right">
                                    <div class="text-white font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                    <div class="mt-1">
                                        @if($booking->status == 'paid' || $booking->status == 'confirmed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-green-500/10 text-green-400 border border-green-500/20 uppercase tracking-wider">Confirmed</span>
                                        @elseif($booking->status == 'pending' || $booking->status == 'unpaid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">Status: Pending</span>
                                        @elseif($booking->status == 'done')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase tracking-wider">Completed</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-zinc-500/10 text-zinc-400 border border-zinc-500/20 uppercase tracking-wider">{{ $booking->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <div class="text-zinc-500 mb-1">Date & Time</div>
                                    <div class="text-zinc-300 font-medium flex items-center gap-2">
                                        <i class='bx bx-calendar text-amber-500'></i>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('D, M d Y') }}
                                        <span class="text-zinc-500">•</span>
                                        {{ substr($booking->booking_time, 0, 5) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-zinc-500 mb-1">Assigned Barber</div>
                                    <div class="text-zinc-300 font-medium flex items-center gap-2">
                                        <i class='bx bx-user text-amber-500'></i>
                                        {{ $booking->staff->name ?? 'Any Available Barber' }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($booking->status == 'pending')
                                <div class="mt-5 pt-4 border-t border-zinc-800/50 flex justify-end">
                                    <a href="{{ route('payment.process', $booking->id) }}" class="text-sm bg-amber-500 text-zinc-900 font-medium px-4 py-2 rounded-lg hover:bg-amber-400 transition-colors">
                                        Pay Now
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center border-2 border-dashed border-zinc-800 rounded-xl">
                            <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 text-zinc-500">
                                <i class='bx bx-calendar-x text-3xl'></i>
                            </div>
                            <h3 class="text-white font-medium mb-1">No Bookings Found</h3>
                            <p class="text-zinc-400 text-sm mb-6">You haven't made any appointments yet.</p>
                            <a href="{{ route('booking.wizard') }}" class="btn-primary text-sm px-6 py-2">Book Now</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
