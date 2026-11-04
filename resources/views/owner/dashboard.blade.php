@extends('layouts.app')

@section('title', 'Dashboard Pemilik Kontrakan')

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-brand-50 text-brand-700 border border-brand-200">Panel Kontrol Pemilik</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Dashboard Pengelolaan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau performa properti, booking sewa masuk, dan pendapatan Anda.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('owner.notifications.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs rounded-xl shadow-sm border border-slate-200 transition-all">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>Notifikasi</span>
                    @if(!empty($navbarUnreadNotificationsCount) && $navbarUnreadNotificationsCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-rose-500 text-white">
                            {{ $navbarUnreadNotificationsCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('owner.properties.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Kontrakan</span>
                </a>
                <a href="{{ route('owner.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Daftar Booking ({{ $stats['pending_bookings'] }})</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Top Statistics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Properti</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['total_properties'] }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Unit terdaftar</span>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Siap Dihuni</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['available_properties'] }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Unit tersedia</span>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block">Terisi / Dihuni</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 mt-2">{{ $stats['occupied_properties'] }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Penyewa aktif</span>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block">Menunggu Respon</span>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['pending_bookings'] }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Permohonan baru</span>
        </div>

        <!-- Card 5 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm col-span-2 lg:col-span-1">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block">Estimasi Omset/Bln</span>
            <p class="text-xl sm:text-2xl font-extrabold text-brand-600 mt-2 truncate">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Dari unit aktif</span>
        </div>
    </div>

    <!-- Permohonan Booking Masuk (Urgent Action Required) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Permohonan Sewa Masuk (Menunggu Verifikasi)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Konfirmasi permohonan atau verifikasi bukti transfer pembayaran dari calon penyewa.</p>
            </div>
            <a href="{{ route('owner.bookings.index', ['status' => 'pending']) }}" class="text-xs font-bold text-brand-600 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        @if($pendingBookings->isEmpty())
            <div class="py-8 text-center text-xs text-slate-400">
                Tidak ada permohonan booking yang pending saat ini. Semua pesanan telah ditindaklanjuti!
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase">
                            <th class="pb-3">Kode & Tanggal</th>
                            <th class="pb-3">Penyewa</th>
                            <th class="pb-3">Kontrakan</th>
                            <th class="pb-3">Mulai & Durasi</th>
                            <th class="pb-3">Total Biaya</th>
                            <th class="pb-3">Status Bayar</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pendingBookings as $b)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 font-mono font-bold text-slate-900">
                                    #{{ $b->booking_code }}
                                    <span class="block font-sans text-[10px] text-slate-400 font-normal">{{ $b->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="font-bold text-slate-800">{{ $b->tenant_name }}</span>
                                    <span class="block text-[10px] text-slate-500">{{ $b->tenant_phone }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="font-semibold text-slate-800">{{ $b->property->title }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $b->property->city }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-slate-700 font-medium">{{ $b->start_date->format('d M Y') }}</span>
                                    <span class="block text-[10px] text-slate-500">{{ $b->duration_months }} Bulan</span>
                                </td>
                                <td class="py-3 font-bold text-brand-600">
                                    {{ $b->formatted_grand_total }}
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $b->payment_status_badge_class }}">
                                        {{ $b->payment_status_label }}
                                    </span>
                                </td>
                                <td class="py-3 text-right space-x-1">
                                    <!-- Setujui Button -->
                                    <form action="{{ route('owner.bookings.confirm', $b->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-lg shadow-2xs">
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Verifikasi Pembayaran jika bukti diupload -->
                                    @if($b->payment_status === 'paid')
                                        <form action="{{ route('owner.bookings.verify-payment', $b->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-[11px] rounded-lg shadow-2xs" title="Verifikasi Lunas & Aktifkan">
                                                Verifikasi Lunas
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Detail link -->
                                    <a href="{{ route('bookings.show', $b->booking_code) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg inline-block">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Two Columns: Recent Bookings & Reviews -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Riwayat Pesanan Terbaru</h3>
                <a href="{{ route('owner.bookings.index') }}" class="text-xs font-bold text-brand-600 hover:underline">Semua &rarr;</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentBookings as $rb)
                    <div class="py-3 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-900">#{{ $rb->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold border {{ $rb->status_badge_class }}">{{ $rb->status_label }}</span>
                            </div>
                            <p class="text-xs font-bold text-slate-800 mt-1">{{ $rb->tenant_name }} &bull; <span class="text-slate-500 font-normal">{{ $rb->property->title }}</span></p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-slate-900">{{ $rb->formatted_grand_total }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $rb->duration_months }} Bulan</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada riwayat pesanan.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Ulasan Penyewa Terbaru</h3>
                <span class="text-xs text-slate-400">{{ $recentReviews->count() }} Ulasan</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentReviews as $rv)
                    <div class="py-3 space-y-1 first:pt-0 last:pb-0">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">{{ $rv->user->name }}</span>
                            <div class="flex items-center text-amber-500 text-xs">
                                @for($i = 0; $i < $rv->rating; $i++)
                                    &starf;
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 italic line-clamp-2">"{{ $rv->comment }}"</p>
                        <p class="text-[10px] text-brand-600 font-medium">{{ $rv->property->title }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada ulasan dari penyewa.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
