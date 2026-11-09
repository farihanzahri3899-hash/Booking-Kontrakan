@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-tr from-brand-700 to-brand-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/20 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-xs text-slate-500 mt-1">Masuk untuk mengelola pesanan atau properti kontrakan Anda</p>
        </div>

        <!-- Demo Accounts Quick Fill Buttons -->
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 space-y-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">Akun Demo Pengujian Cepat:</span>
            <div class="grid grid-cols-3 gap-2">
                <a href="{{ route('quick-login', 'admin') }}" class="px-2 py-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl text-[11px] font-bold text-indigo-900 text-center transition-colors shadow-2xs">
                    Admin
                </a>
                <a href="{{ route('quick-login', 'pemilik') }}" class="px-2 py-2 bg-white hover:bg-amber-100/70 border border-amber-300 rounded-xl text-[11px] font-bold text-amber-900 text-center transition-colors shadow-2xs">
                    Pemilik
                </a>
                <a href="{{ route('quick-login', 'penyewa') }}" class="px-2 py-2 bg-white hover:bg-amber-100/70 border border-amber-300 rounded-xl text-[11px] font-bold text-amber-900 text-center transition-colors shadow-2xs">
                    Penyewa
                </a>
            </div>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-7">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500">
                        <span>Ingat saya</span>
                    </label>

                    <span class="text-brand-600 hover:underline cursor-pointer">Lupa password?</span>
                </div>

                <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-md shadow-brand-600/30 transition-all hover:scale-[1.01] mt-2">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:underline ml-1">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
