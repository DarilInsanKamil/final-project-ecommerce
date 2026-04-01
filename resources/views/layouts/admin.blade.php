<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BarberKu Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Using Boxicons for icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/app.css'])
</head>
<body class="antialiased font-body bg-zinc-950 text-zinc-100 flex h-screen overflow-hidden selection:bg-amber-500 selection:text-zinc-900">
    <!-- Sidebar -->
    <aside class="w-64 bg-zinc-900 border-r border-zinc-800 flex flex-col h-full">
        <div class="h-16 flex items-center px-6 border-b border-zinc-800">
            <span class="text-xl font-bold tracking-tight text-white flex items-center">
                Barber<span class="text-amber-500">Ku</span>
                <span class="ml-2 text-xs font-normal text-zinc-500 bg-zinc-800 px-2 py-1 rounded">Admin</span>
            </span>
        </div>
        <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/10 text-amber-500' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} transition-colors">
                <i class='bx bxs-dashboard text-lg'></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.bookings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.bookings') ? 'bg-amber-500/10 text-amber-500' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} transition-colors">
                <i class='bx bxs-calendar text-lg'></i>
                <span class="font-medium">Bookings</span>
            </a>
            <a href="{{ route('admin.services') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.services') ? 'bg-amber-500/10 text-amber-500' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} transition-colors">
                <i class='bx bxs-cut text-lg'></i>
                <span class="font-medium">Services</span>
            </a>
            <a href="{{ route('admin.staff') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.staff') ? 'bg-amber-500/10 text-amber-500' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} transition-colors">
                <i class='bx bxs-group text-lg'></i>
                <span class="font-medium">Staff</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-amber-500/10 text-amber-500' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} transition-colors">
                <i class='bx bxs-user-plus text-lg'></i>
                <span class="font-medium">Create User</span>
            </a>
        </nav>
        <div class="p-4 border-t border-zinc-800">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="text-sm">
                    <div class="font-medium text-white">{{ auth()->user()->name ?? 'Admin User' }}</div>
                    <div class="text-zinc-500">{{ auth()->user()->email ?? 'admin@barberku.com' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-red-500/10 rounded-lg transition-colors flex items-center gap-2">
                    <i class='bx bx-log-out'></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-zinc-950">
        <!-- Top Header -->
        <header class="h-16 bg-zinc-900 border-b border-zinc-800 flex items-center justify-between px-8">
            <h1 class="text-lg font-semibold text-zinc-100">@yield('title', 'Admin Dashboard')</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="text-sm text-zinc-400 hover:text-amber-500 transition-colors flex items-center gap-1">
                    <i class='bx bx-link-external'></i> View Site
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-auto p-8">
            @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
                <i class='bx bx-check-circle text-xl'></i>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 flex items-center gap-3">
                <i class='bx bx-error-circle text-xl'></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>
    
    @stack('scripts')
</body>
</html>
