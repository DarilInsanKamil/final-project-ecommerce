<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BarberKu | Premium Salon Booking</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body
    class="antialiased font-body bg-zinc-900 text-zinc-100 min-h-screen flex flex-col selection:bg-amber-500 selection:text-zinc-900">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 border-b border-zinc-800 bg-zinc-900/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-amber-500 flex flex-col items-center justify-center">
                        <span class="text-zinc-900 font-bold text-lg leading-none">B</span>
                    </div>
                    <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-white flex items-center">
                        Barber<span class="text-amber-500">Ku</span>
                    </a>
                </div>
                <div class="hidden lg:flex justify-between items-center space-x-6 lg:space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-zinc-300 hover:text-amber-500 transition-colors">Home</a>
                    <a href="{{ route('services') }}"
                        class="text-sm font-medium text-zinc-300 hover:text-amber-500 transition-colors">Services</a>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-sm font-medium text-amber-500 hover:text-amber-400 transition-colors border border-amber-500/30 px-3 py-1.5 rounded-lg bg-amber-500/10">Admin
                                Panel</a>
                        @elseif(auth()->user()->role === 'staff')
                            <a href="#"
                                class="text-sm font-medium text-amber-500 hover:text-amber-400 transition-colors border border-amber-500/30 px-3 py-1.5 rounded-lg bg-amber-500/10">Staff
                                Panel</a>
                        @else
                            <a href="{{ route('user.profile') }}"
                                class="text-sm font-medium text-zinc-300 flex items-center gap-2 hover:text-amber-500 transition-colors border border-zinc-700 bg-zinc-800/50 px-3 py-1.5 rounded-full">
                                <i class='bx bxs-user-circle text-lg'></i>
                                {{ strtok(auth()->user()->name, ' ') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-zinc-300 hover:text-amber-500 transition-colors">Login</a>
                    @endauth
                    <a href="{{ route('booking.wizard') }}" class="btn-primary text-sm py-2 px-5">Book Now</a>
                </div>

                <!-- Mobile button -->
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-zinc-300 hover:text-white focus:outline-none">
                        <i class='bx bx-menu text-3xl'></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden border-t border-zinc-800 bg-zinc-900 pb-4 px-4 pt-2 space-y-3 shadow-xl">
            <a href="{{ route('home') }}"
                class="block text-base font-medium text-zinc-300 hover:text-amber-500 transition-colors">Home</a>
            <a href="{{ route('services') }}"
                class="block text-base font-medium text-zinc-300 hover:text-amber-500 transition-colors">Services</a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="block text-base font-medium text-amber-500 hover:text-amber-400 transition-colors">Admin
                        Panel</a>
                @elseif(auth()->user()->role === 'staff')
                    <a href="#" class="block text-base font-medium text-amber-500 hover:text-amber-400 transition-colors">Staff
                        Panel</a>
                @else
                    <a href="{{ route('user.profile') }}"
                        class="block text-base font-medium text-zinc-300 hover:text-amber-500 transition-colors">My Profile</a>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="block text-base font-medium text-zinc-300 hover:text-amber-500 transition-colors">Login</a>
            @endauth
            <div class="pt-2">
                <a href="{{ route('booking.wizard') }}" class="btn-primary block text-center py-2.5">Book
                    Appointment</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-950 border-t border-zinc-900 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <span class="text-xl font-bold tracking-tight text-white flex items-center">
                        Barber<span class="text-amber-500">Ku</span>
                    </span>
                </div>
                <div class="text-zinc-500 text-sm">
                    &copy; {{ date('Y') }} BarberKu. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>