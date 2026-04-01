@extends('layouts.app')

@section('title', 'Services - BarberKu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Our Premium Services</h1>
        <p class="text-zinc-400 max-w-2xl">Browse our catalog of expert grooming services tailored to your needs.</p>
    </div>

    <!-- Filters (Static for MVP) -->
    <div class="flex flex-wrap gap-4 mb-10 border-b border-zinc-800 pb-6">
        <a href="{{ route('services', ['category' => 'all']) }}" class="px-5 py-2 rounded-full text-sm font-medium {{ request('category', 'all') == 'all' ? 'bg-amber-500 text-zinc-900' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700 hover:text-white' }} transition-colors">
            All Services
        </a>
        <a href="{{ route('services', ['category' => 'Haircut']) }}" class="px-5 py-2 rounded-full text-sm font-medium {{ request('category') == 'Haircut' ? 'bg-amber-500 text-zinc-900' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700 hover:text-white' }} transition-colors">
            Haircuts
        </a>
        <a href="{{ route('services', ['category' => 'Beard']) }}" class="px-5 py-2 rounded-full text-sm font-medium {{ request('category') == 'Beard' ? 'bg-amber-500 text-zinc-900' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700 hover:text-white' }} transition-colors">
            Beard Trims
        </a>
        <a href="{{ route('services', ['category' => 'Treatment']) }}" class="px-5 py-2 rounded-full text-sm font-medium {{ request('category') == 'Treatment' ? 'bg-amber-500 text-zinc-900' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700 hover:text-white' }} transition-colors">
            Treatments
        </a>
    </div>

    <!-- Catalog -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($services as $service)
        <div class="card flex flex-col group cursor-pointer" onclick="window.location='{{ route('booking.wizard') }}?service={{ $service->id }}'">
            <div class="relative w-full h-48 mb-6 rounded-xl overflow-hidden bg-zinc-900 border border-zinc-700">
                @if($service->image_url)
                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-zinc-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                @endif
                <div class="absolute top-4 right-4 bg-zinc-900/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-amber-500 border border-zinc-700">
                    {{ $service->category }}
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-amber-500 transition-colors">{{ $service->name }}</h3>
            <p class="text-zinc-400 text-sm mb-6 flex-grow">{{ $service->description }}</p>
            
            <div class="flex items-center justify-between pt-4 border-t border-zinc-800/50 mt-auto">
                <div>
                    <div class="text-xs text-zinc-500 mb-1">Starting from</div>
                    <div class="text-xl font-bold text-white">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-zinc-500 mb-1">Duration</div>
                    <div class="text-sm font-medium text-zinc-300">{{ $service->duration_minutes }} Mins</div>
                </div>
            </div>
            
            <button class="w-full mt-6 py-3 rounded-lg border border-amber-500 text-amber-500 hover:bg-amber-500 hover:text-zinc-900 font-semibold transition-all">
                Book This Service
            </button>
        </div>
        @empty
            <div class="col-span-full py-12 text-center text-zinc-500 bg-zinc-900/30 rounded-2xl border border-dashed border-zinc-800">
                <i class='bx bx-cut text-4xl mb-3'></i>
                <p>No services found for this category.</p>
                <a href="{{ route('services') }}" class="text-amber-500 hover:underline mt-2 inline-block">Clear filters</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
