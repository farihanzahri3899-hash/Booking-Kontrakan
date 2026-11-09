@extends('layouts.app')

@section('title', '403 - Akses Terbatas')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <!-- Shield / Lock Icon -->
        <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/10 mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <!-- Error Code & Title -->
        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest bg-rose-50 text-rose-700 border border-rose-200 mb-3">
            Error 403 : Forbidden
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Akses Dibatasi
        </h1>

        <!-- Explanation -->
        <p class="text-sm text-slate-600 mt-3 leading-relaxed">
            {{ $exception->getMessage() ?: 'Halaman ini hanya dapat diakses oleh Pemilik Kontrakan atau Administrator.' }}
        </p>

        @auth
            <div class="mt-4 p-3 bg-slate-100 rounded-xl text-xs text-slate-600">
                Anda saat ini login sebagai: <span class="font-bold text-slate-900">{{ auth()->user()->name }}</span> (Role: <span class="font-bold uppercase text-brand-700">{{ auth()->user()->role }}</span>)
            </div>
        @endauth

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('quick-login', 'pemilik') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-md shadow-brand-600/30 transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span>Masuk Akun Pemilik (Demo)</span>
            </a>

            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-xl border border-slate-300 shadow-xs transition-all">
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</div>
@endsection
