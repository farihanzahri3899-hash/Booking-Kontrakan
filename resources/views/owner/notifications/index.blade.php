@extends('layouts.app')

@section('title', 'Pusat Notifikasi Pemilik')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Beranda</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('owner.dashboard') }}" class="hover:text-brand-600 transition-colors">Panel Pemilik</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 font-semibold">Notifikasi</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                        <span>Pusat Notifikasi Booking</span>
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500 text-white shadow-sm shadow-rose-500/20">
                                {{ $unreadCount }} Baru
                            </span>
                        @endif
                    </h1>
                    <p class="text-sm text-slate-600 mt-1">
                        Pemberitahuan real-time setiap kali calon penyewa melakukan booking Rumah Kontrakan, Kost Eksklusif, Kontrakan Petakan, atau Paviliun Mandiri.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2.5">
                    @if($unreadCount > 0)
                        <form action="{{ route('owner.notifications.read-all') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl shadow-sm transition-all hover:border-slate-400">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Tandai Semua Sudah Dibaca</span>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('owner.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm shadow-brand-600/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Kelola Semua Booking</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 mb-6 pb-2">
            <a href="{{ route('owner.notifications.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter !== 'unread' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Semua Notifikasi ({{ $totalCount }})
            </a>
            <a href="{{ route('owner.notifications.index', ['filter' => 'unread']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 {{ $filter === 'unread' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span>Belum Dibaca</span>
                @if($unreadCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-xs font-bold {{ $filter === 'unread' ? 'bg-rose-500 text-white' : 'bg-rose-100 text-rose-700' }}">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Notification List -->
        @if($notifications->count() > 0)
            <div class="space-y-3.5">
                @foreach($notifications as $notif)
                    @php
                        $data = $notif->data;
                        $isUnread = is_null($notif->read_at);
                        $type = $data['property_type'] ?? 'rumah';
                        
                        // Badge color mapping by property type
                        $typeBadgeColors = match($type) {
                            'rumah' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'kost' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'petakan' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'paviliun' => 'bg-purple-50 text-purple-700 border-purple-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                        };

                        $typeIconColors = match($type) {
                            'rumah' => 'bg-emerald-100 text-emerald-700',
                            'kost' => 'bg-indigo-100 text-indigo-700',
                            'petakan' => 'bg-amber-100 text-amber-700',
                            'paviliun' => 'bg-purple-100 text-purple-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp

                    <div class="relative bg-white rounded-2xl border transition-all duration-200 p-4 sm:p-5 {{ $isUnread ? 'border-brand-300 ring-2 ring-brand-500/10 shadow-sm bg-gradient-to-r from-brand-50/40 via-white to-white' : 'border-slate-200/90 hover:border-slate-300 shadow-sm' }}">
                        
                        @if($isUnread)
                            <div class="absolute top-4 right-4 flex items-center gap-1.5">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                                </span>
                                <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Baru</span>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                            <!-- Type Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl {{ $typeIconColors }} flex items-center justify-center shadow-inner">
                                    @if($type === 'rumah')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    @elseif($type === 'kost')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    @elseif($type === 'petakan')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Content Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold border {{ $typeBadgeColors }}">
                                        {{ $data['property_type_label'] ?? 'Kontrakan' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $data['booking_code'] ?? '-' }}
                                    </span>
                                    <span class="text-xs text-slate-600 flex items-center gap-1 ml-auto sm:ml-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h2 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                                    {{ $data['property_title'] ?? 'Kontrakan' }}
                                </h2>

                                <p class="text-sm text-slate-600 mt-0.5">
                                    {{ $data['message'] ?? 'Ada pesanan masuk baru.' }}
                                </p>

                                <!-- Details grid -->
                                <div class="mt-3.5 grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 border-t border-slate-100 text-xs">
                                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                        <span class="block text-slate-600 font-medium">Calon Penyewa:</span>
                                        <span class="font-bold text-slate-900 truncate block mt-0.5">{{ $data['tenant_name'] ?? '-' }}</span>
                                    </div>
                                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                        <span class="block text-slate-600 font-medium">Nomor WhatsApp:</span>
                                        <span class="font-bold text-slate-900 truncate block mt-0.5">{{ $data['tenant_phone'] ?? '-' }}</span>
                                    </div>
                                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                        <span class="block text-slate-600 font-medium">Durasi Sewa:</span>
                                        <span class="font-bold text-slate-900 block mt-0.5">{{ $data['duration_months'] ?? 1 }} Bulan</span>
                                    </div>
                                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                        <span class="block text-slate-600 font-medium">Total Tagihan:</span>
                                        <span class="font-bold text-brand-700 block mt-0.5">{{ $data['formatted_grand_total'] ?? '-' }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons row -->
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('owner.notifications.read-redirect', $notif->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition-all">
                                            <span>Lihat Detail & Konfirmasi</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>

                                        @if($isUnread)
                                            <form action="{{ route('owner.notifications.read', $notif->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                                    Tandai Sudah Dibaca
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <form action="{{ route('owner.notifications.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-600 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Notifikasi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm max-w-xl mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-100 text-brand-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">
                    {{ $filter === 'unread' ? 'Tidak Ada Notifikasi Baru' : 'Belum Ada Notifikasi' }}
                </h3>
                <p class="text-sm text-slate-500 mb-6">
                    {{ $filter === 'unread' ? 'Semua notifikasi booking Anda telah dibaca.' : 'Saat calon penyewa melakukan booking pada properti kontrakan Anda, notifikasi akan langsung muncul di sini.' }}
                </p>
                @if($filter === 'unread')
                    <a href="{{ route('owner.notifications.index', ['filter' => 'all']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Lihat Semua Riwayat Notifikasi
                    </a>
                @else
                    <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-sm shadow-brand-600/30 transition-all">
                        Kembali ke Panel Pemilik
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
