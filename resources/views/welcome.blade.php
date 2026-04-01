@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[85vh] flex items-center overflow-hidden">
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-3xl -top-40 -right-20 animate-pulse" style="animation-duration: 4s;"></div>
        <div class="absolute w-[500px] h-[500px] bg-amber-600/5 rounded-full blur-3xl bottom-0 -left-40"></div>
        <!-- Dot pattern overlay -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 32px 32px;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-500 text-sm font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Premium Grooming Experience
                </div>
                <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
                    Elevate Your <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">Style & Confidence</span>
                </h1>
                <p class="text-lg text-zinc-400 mb-8 leading-relaxed max-w-lg">
                    Experience world-class grooming with our expert barbers. Precision cuts, hot towel shaves, and a legendary atmosphere.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('booking.wizard') }}" class="btn-primary text-center text-lg py-3 px-8 flex items-center justify-center gap-2 group">
                        Book Appointment
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('services') }}" class="btn-secondary text-center text-lg py-3 px-8">
                        View Services
                    </a>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <!-- Decorative Image Frame -->
                <div class="relative w-full aspect-[4/5] rounded-3xl overflow-hidden border border-zinc-800 bg-zinc-900 shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 via-transparent to-transparent z-10"></div>
                    <!-- Assuming user provides image later, using a highly styled placeholder -->
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Barber at work" class="w-full h-full object-cover opacity-80 mix-blend-luminosity hover:mix-blend-normal hover:scale-105 transition-all duration-700">
                    
                    <div class="absolute bottom-8 left-8 right-8 z-20">
                        <div class="bg-zinc-900/80 backdrop-blur-md border border-zinc-700/50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-500 flex items-center justify-center rounded-lg text-zinc-900 font-bold text-xl">
                                4.9
                            </div>
                            <div>
                                <div class="text-white font-medium">Customer Review</div>
                                <div class="text-amber-500 text-sm flex gap-1">
                                    ★ ★ ★ ★ ★
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services -->
<section class="py-24 bg-zinc-950 relative border-t border-zinc-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Our Services</h2>
            <p class="text-zinc-400 max-w-2xl mx-auto">Tailored specifically for the modern gentleman.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredServices as $service)
            <div class="card group cursor-pointer" onclick="window.location='{{ route('booking.wizard') }}?service={{ $service->id }}'">
                <div class="w-14 h-14 bg-zinc-900 rounded-xl flex items-center justify-center mb-6 pt-1 text-amber-500 border border-zinc-700 group-hover:bg-amber-500 group-hover:text-zinc-900 group-hover:border-amber-500 transition-all">
                    <!-- Placeholder Icon -->
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">{{ $service->name }}</h3>
                <p class="text-zinc-400 text-sm mb-4 line-clamp-2">{{ $service->description ?? 'Premium service.' }}</p>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-amber-500 font-semibold text-lg">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                    <span class="text-zinc-500 text-xs flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $service->duration_minutes }}m
                    </span>
                </div>
            </div>
            @empty
            <!-- Demo Content -->
            <div class="card group">
                <div class="w-14 h-14 bg-zinc-900 rounded-xl flex items-center justify-center mb-6 pt-1 text-amber-500 border border-zinc-700">
                    <span class="text-2xl font-bold">✂</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Signature Haircut</h3>
                <p class="text-zinc-400 text-sm mb-4">Precision cut tailored to your face shape.</p>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-amber-500 font-semibold text-lg">Rp 75.000</span>
                    <span class="text-zinc-500 text-xs">45m</span>
                </div>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('services') }}" class="text-amber-500 hover:text-amber-400 font-medium inline-flex items-center gap-2">
                View All Services
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-24 relative overflow-hidden bg-amber-500">
    <div class="absolute inset-0 z-0 opacity-10" style="background-image: radial-gradient(#000 2px, transparent 2px); background-size: 24px 24px;"></div>
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-6">Ready for a transformation?</h2>
        <p class="text-xl text-zinc-800 mb-10">Book your slot today and experience the BarberKu difference.</p>
        <a href="{{ route('booking.wizard') }}" class="bg-zinc-900 text-white hover:bg-zinc-800 text-lg py-4 px-10 rounded-xl font-semibold transition-colors shadow-xl">
            Book Your Appointment
        </a>
    </div>
</section>
@endsection
