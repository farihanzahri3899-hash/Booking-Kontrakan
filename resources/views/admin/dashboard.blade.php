@extends('layouts.app')

@section('title', 'Dashboard Administrator - Kelola Izin Pemilik')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Header & Breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Beranda</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 font-semibold">Panel Administrator</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-indigo-100 text-indigo-800 border border-indigo-200">
                            Super Administrator
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Pusat Kontrol & Manajemen Izin Akun
                    </h1>
                    <p class="text-sm text-slate-600 mt-1">
                        Verifikasi dan berikan izin role <span class="font-bold text-slate-900">Pemilik Kontrakan</span> kepada pengguna terdaftar, serta pantau seluruh aktivitas platform.
                    </p>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl shadow-xs border border-slate-300 transition-all">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Lihat Panel Pemilik</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Katalog Kontrakan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Overview Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <!-- Total Users -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pengguna</span>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['total_users'] }}</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Akun terdaftar</span>
            </div>

            <!-- Pemilik -->
            <div class="bg-white p-5 rounded-2xl border border-emerald-200/90 bg-gradient-to-br from-white via-white to-emerald-50/30 shadow-xs">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Pemilik Kontrakan</span>
                <p class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-2">{{ $stats['total_owners'] }}</p>
                <span class="text-[11px] text-emerald-600/80 mt-1 block">Diberi izin kelola unit</span>
            </div>

            <!-- Penyewa -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block">Penyewa Publik</span>
                <p class="text-2xl sm:text-3xl font-extrabold text-blue-700 mt-2">{{ $stats['total_tenants'] }}</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Calon & penghuni sewa</span>
            </div>

            <!-- Properties -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block">Unit Kontrakan</span>
                <p class="text-2xl sm:text-3xl font-extrabold text-amber-700 mt-2">{{ $stats['total_properties'] }}</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Terdaftar di platform</span>
            </div>

            <!-- Bookings -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs col-span-2 lg:col-span-1">
                <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block">Total Booking</span>
                <p class="text-2xl sm:text-3xl font-extrabold text-purple-700 mt-2">{{ $stats['total_bookings'] }}</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Transaksi sewa dibuat</span>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Daftar Pengguna & Pemberian Izin Pemilik</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Pendaftaran umum otomatis sebagai Penyewa. Berikan izin di bawah ini agar pengguna dapat menambahkan kontrakan baru.
                        </p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        @if($roleFilter)
                            <input type="hidden" name="role" value="{{ $roleFilter }}">
                        @endif
                        <div class="relative">
                            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, hp..." class="w-64 pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-colors">
                            Cari
                        </button>
                        @if($search || $roleFilter)
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Role Filter Tabs -->
                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.dashboard', array_filter(['q' => $search])) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ empty($roleFilter) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Semua Pengguna ({{ $stats['total_users'] }})
                    </a>
                    <a href="{{ route('admin.dashboard', array_filter(['role' => 'penyewa', 'q' => $search])) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $roleFilter === 'penyewa' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Penyewa ({{ $stats['total_tenants'] }})
                    </a>
                    <a href="{{ route('admin.dashboard', array_filter(['role' => 'pemilik', 'q' => $search])) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $roleFilter === 'pemilik' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Pemilik Kontrakan ({{ $stats['total_owners'] }})
                    </a>
                    <a href="{{ route('admin.dashboard', array_filter(['role' => 'admin', 'q' => $search])) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $roleFilter === 'admin' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Administrator ({{ $stats['total_admins'] }})
                    </a>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-6">Pengguna</th>
                            <th class="py-3.5 px-4">Kontak WhatsApp</th>
                            <th class="py-3.5 px-4">Role Saat Ini</th>
                            <th class="py-3.5 px-4">Terdaftar Sejak</th>
                            <th class="py-3.5 px-6 text-right">Otorisasi & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- User Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=059669&color=fff' }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 flex-shrink-0">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm leading-tight flex items-center gap-1.5">
                                                <span>{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Anda</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-slate-500 font-normal mt-0.5">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Phone -->
                                <td class="py-4 px-4 font-mono text-slate-700">
                                    {{ $user->phone ?? '-' }}
                                </td>

                                <!-- Current Role -->
                                <td class="py-4 px-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                            Administrator
                                        </span>
                                    @elseif($user->role === 'pemilik')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Pemilik Kontrakan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Penyewa
                                        </span>
                                    @endif
                                </td>

                                <!-- Registered At -->
                                <td class="py-4 px-4 text-slate-600">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                    <span class="block text-[11px] text-slate-400 font-normal">
                                        {{ $user->created_at ? $user->created_at->diffForHumans() : '' }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($user->role === 'penyewa')
                                            <!-- Tombol Beri Izin Pemilik -->
                                            <form action="{{ route('admin.users.grant-owner', $user->id) }}" method="POST" onsubmit="return confirm('Beri izin akun {{ $user->name }} menjadi Pemilik Kontrakan?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all hover:scale-105">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>Beri Izin Pemilik</span>
                                                </button>
                                            </form>
                                        @elseif($user->role === 'pemilik')
                                            <!-- Tombol Cabut Izin Pemilik -->
                                            <form action="{{ route('admin.users.revoke-owner', $user->id) }}" method="POST" onsubmit="return confirm('Cabut izin pemilik dari {{ $user->name }} dan kembalikan menjadi Penyewa?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs rounded-xl border border-amber-200 transition-colors">
                                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    <span>Cabut Izin Pemilik</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400 italic">Super User</span>
                                        @endif

                                        @if($user->id !== auth()->id())
                                            <!-- Tombol Hapus Akun -->
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Akun Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="font-bold text-slate-800">Tidak ada pengguna ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Coba gunakan kata kunci pencarian lain atau ganti filter role.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
