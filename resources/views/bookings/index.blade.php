@extends('layouts.app')

@section('title', 'Riwayat Pesanan Sewa Saya')

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pesanan Sewa Saya</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau status pengajuan sewa, invoice pembayaran, dan riwayat kontrakan Anda.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 border-b border-slate-200">
        <a href="{{ route('bookings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ !$status ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Konfirmasi ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('bookings.index', ['status' => 'active']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'active' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Sedang Berjalan ({{ $counts['active'] }})
        </a>
        <a href="{{ route('bookings.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'completed' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Selesai ({{ $counts['completed'] }})
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Belum Ada Riwayat Booking</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">Anda belum melakukan pengajuan sewa untuk kategori ini. Temukan kontrakan yang cocok sekarang!</p>
            <div class="mt-6">
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                    Cari Kontrakan Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <!-- Left: Property Info -->
                    <div class="flex items-start sm:items-center gap-4">
                        <img src="{{ $booking->property->main_image }}" alt="{{ $booking->property->title }}" class="w-20 h-20 rounded-xl object-cover shrink-0">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="font-mono text-xs font-bold text-slate-900">#{{ $booking->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $booking->status_badge_class }}">
                                    {{ $booking->status_label }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $booking->payment_status_badge_class }}">
                                    {{ $booking->payment_status_label }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 hover:text-brand-600 transition-colors">
                                <a href="{{ route('bookings.show', $booking->booking_code) }}">
                                    {{ $booking->property->title }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 mt-0.5">{{ $booking->property->city }} &bull; Mulai {{ $booking->start_date->format('d M Y') }} ({{ $booking->duration_months }} Bulan)</p>
                        </div>
                    </div>

                    <!-- Right: Price & Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-4 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <div class="text-left md:text-right">
                            <span class="block text-[10px] font-medium text-slate-400">Total Pembayaran</span>
                            <span class="text-base font-extrabold text-brand-600">{{ $booking->formatted_grand_total }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('bookings.show', $booking->booking_code) }}" class="px-4 py-2 bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors text-center">
                                Detail Faktur
                            </a>

                            @if(in_array($booking->status, ['active', 'completed']) && !$booking->review)
                                <a href="{{ route('properties.show', $booking->property->slug) }}#review" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs rounded-xl border border-amber-200 transition-colors text-center">
                                    Beri Ulasan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
