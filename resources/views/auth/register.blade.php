@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-tr from-brand-700 to-brand-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/20 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Akun KontrakanID</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar sekarang untuk mulai mencari dan menyewa kontrakan impian</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-7">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Info Notice -->
                <div class="p-3 rounded-2xl bg-brand-50 border border-brand-100 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-brand-800 leading-relaxed">
                        Pendaftaran akun publik otomatis sebagai <span class="font-bold">Penyewa</span>. Izin akun Pemilik Kontrakan diverifikasi dan diberikan khusus oleh Administrator.
                    </p>
                </div>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password di atas" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div class="text-[11px] text-slate-500 leading-relaxed pt-1">
                    Dengan mendaftar, Anda menyetujui <span class="text-brand-600 underline cursor-pointer">Syarat & Ketentuan</span> serta Kebijakan Privasi platform KontrakanID.
                </div>

                <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-md shadow-brand-600/30 transition-all hover:scale-[1.01] mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:underline ml-1">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
