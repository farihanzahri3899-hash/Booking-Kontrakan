@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Pengaturan Profil</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Profil & Akun</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola informasi data pribadi, kontak WhatsApp, foto profil, dan keamanan sandi akun Anda.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{
    avatarPreview: '{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=059669&color=fff' }}',
    updatePreview(event) {
        const file = event.target.files[0];
        if (file) {
            this.avatarPreview = URL.createObjectURL(file);
        }
    }
}">
    <!-- Top User Overview Card -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-brand-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-5 text-center sm:text-left">
            <div class="relative shrink-0">
                <img :src="avatarPreview" alt="{{ $user->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border-2 border-brand-400 shadow-md">
                <span class="absolute -bottom-2 -right-2 px-2 py-0.5 bg-brand-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">
                    {{ $user->role }}
                </span>
            </div>
            <div>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white">{{ $user->name }}</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 flex items-center justify-center sm:justify-start gap-2">
                    <span>{{ $user->email }}</span>
                    <span>&bull;</span>
                    <span>{{ $user->phone ?? 'Belum ada no WhatsApp' }}</span>
                </p>
                <p class="text-xs text-brand-300 mt-1">Bergabung sejak {{ $user->created_at->isoFormat('D MMMM Y') ?? $user->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2">
            @if($user->isOwnerOrAdmin())
                <a href="{{ route('owner.dashboard') }}" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    Buka Panel Pemilik &rarr;
                </a>
            @else
                <a href="{{ route('bookings.index') }}" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    Pesanan Saya &rarr;
                </a>
            @endif
        </div>
    </div>

    <!-- Forms Grid: Profile Info & Password Update -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Edit Personal Information (7 Cols) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Informasi Pribadi & Kontak
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pastikan nomor WhatsApp dan nama Anda sesuai agar proses sewa berjalan lancar.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Photo Upload / URL -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <img :src="avatarPreview" alt="Preview" class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                            <div class="flex-1 space-y-2">
                                <input type="file" name="avatar_file" @change="updatePreview" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                                <p class="text-[10px] text-slate-400">Format: JPG, PNG, WEBP (Maksimal 2 MB)</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="avatar_url" class="block text-[11px] font-semibold text-slate-500 mb-1">Atau gunakan URL Foto Eksternal</label>
                            <input type="url" name="avatar_url" id="avatar_url" value="{{ old('avatar_url') }}" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <!-- Phone / WA -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <span class="block text-[11px] text-slate-400 mt-1">Nomor ini digunakan untuk koordinasi survei unit dan penyerahan kunci.</span>
                    </div>

                    <!-- Role Badge (Read Only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Peran Akun</label>
                        <div class="px-3.5 py-2.5 bg-slate-100 rounded-xl text-xs font-bold text-slate-700 flex items-center justify-between">
                            <span class="capitalize">{{ $user->role === 'pemilik' ? 'Pemilik Kontrakan / Mitra' : 'Penyewa Kontrakan' }}</span>
                            <span class="text-[10px] text-slate-400 font-normal">Tipe peran permanen</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all hover:scale-105">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Change Password & Security (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-7 space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Ubah Kata Sandi
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ganti kata sandi secara berkala untuk menjaga keamanan akun Anda.</p>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required placeholder="••••••••" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi kata sandi baru" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-sm transition-colors">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help / Tips Box -->
            <div class="p-5 rounded-2xl bg-brand-50/60 border border-brand-100 text-xs text-slate-600 space-y-2">
                <span class="font-bold text-brand-800 block text-xs">Keamanan Akun:</span>
                <p>&bull; Jangan bagikan kata sandi akun Anda kepada siapapun.</p>
                <p>&bull; Gunakan nomor WhatsApp yang aktif agar notifikasi persetujuan booking dapat segera Anda terima.</p>
            </div>
        </div>
    </div>
</div>
@endsection
