@extends('layouts.app')

@section('title', 'Login - BarberKu')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-zinc-900 border border-zinc-800 p-8 rounded-2xl shadow-xl">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-white">
                Welcome Back
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-400">
                Login to manage your bookings or studio.
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

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-300 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="nama@email.com" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-300 mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-zinc-700 bg-zinc-950 text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-colors" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-amber-500 focus:ring-amber-500 border-zinc-700 bg-zinc-950 rounded">
                    <label for="remember" class="block text-sm text-zinc-400">
                        Remember me
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-zinc-900 bg-amber-500 hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class='bx bxs-lock-alt text-zinc-800 group-hover:text-zinc-900'></i>
                    </span>
                    Sign in
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm text-zinc-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium text-amber-500 hover:text-amber-400 transition-colors">
                    Register here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
