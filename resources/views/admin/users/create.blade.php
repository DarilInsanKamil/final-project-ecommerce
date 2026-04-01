@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="max-w-lg">
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-6 border-b border-zinc-800 pb-4">Create Staff / Admin User</h2>

        @if(session('success'))
            <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-500 p-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Full Name</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Role</label>
                <select name="role" required class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff / Barber</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white outline-none focus:border-amber-500 transition-colors">
            </div>

            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-zinc-900 font-medium rounded-xl py-2.5 transition-colors mt-2">
                Create User
            </button>
        </form>
    </div>
</div>
@endsection
