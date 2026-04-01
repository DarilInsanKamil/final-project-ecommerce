@extends('layouts.app')

@section('title', 'Register - BarberKu')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-zinc-900 border border-zinc-800 p-8 rounded-2xl shadow-xl">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-white">
                Create an Account
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-400">
                Join BarberKu to book your next premium grooming session.
            </p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-zinc-300 mb-1">Full Name</label>
                    <input id="name" name="name" type="text" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="John Doe" value="{{ old('name') }}">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-300 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="nama@email.com" value="{{ old('email') }}">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-zinc-300 mb-1">Phone Number / WhatsApp</label>
                    <input id="phone" name="phone" type="text" class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="08123456789" value="{{ old('phone') }}">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-300 mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="••••••••">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-300 mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-zinc-900 bg-amber-500 hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                    Create Account
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm text-zinc-400">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium text-amber-500 hover:text-amber-400 transition-colors">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
