@extends('layouts.app')

@section('title', 'Kelola Permohonan Booking Sewa')

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('owner.dashboard') }}" class="hover:text-brand-600">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Permohonan Booking</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Permohonan Sewa Masuk</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola verifikasi permohonan sewa, cek bukti transfer, dan konfirmasi penyewa.</p>
            </div>

            <span class="text-xs font-bold text-brand-700 bg-brand-50 border border-brand-200 px-3 py-1.5 rounded-xl self-start sm:self-auto">
                {{ $counts['pending'] }} Permohonan Perlu Tindakan
            </span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ rejectModalOpen: false, rejectBookingId: null, rejectActionUrl: '' }">
    <!-- Status Filter Tabs -->
    <div class="flex items-center justify-between gap-4 flex-wrap border-b border-slate-200 pb-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('owner.bookings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ !$status ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('owner.bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Menunggu Konfirmasi ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('owner.bookings.index', ['status' => 'active']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'active' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Sedang Aktif ({{ $counts['active'] }})
            </a>
            <a href="{{ route('owner.bookings.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'completed' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Selesai ({{ $counts['completed'] }})
            </a>
        </div>

        <form action="{{ route('owner.bookings.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama..." class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
            <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white font-bold text-xs rounded-xl">Cari</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($bookings->isEmpty())
            <div class="p-12 text-center text-xs text-slate-400">
                Tidak ada data permohonan booking untuk kategori ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-6">Kode Booking</th>
                            <th class="py-3.5 px-6">Penyewa</th>
                            <th class="py-3.5 px-6">Kontrakan</th>
                            <th class="py-3.5 px-6">Durasi Sewa</th>
                            <th class="py-3.5 px-6">Total Tagihan</th>
                            <th class="py-3.5 px-6">Bukti Transfer</th>
                            <th class="py-3.5 px-6">Status Booking</th>
                            <th class="py-3.5 px-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Kode & Waktu -->
                                <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                    <a href="{{ route('bookings.show', $booking->booking_code) }}" class="hover:text-brand-600">
                                        #{{ $booking->booking_code }}
                                    </a>
                                    <span class="block font-sans text-[10px] text-slate-400 font-normal mt-0.5">
                                        {{ $booking->created_at->format('d M Y, H:i') }}
                                    </span>
                                </td>

                                <!-- Penyewa -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 text-sm block">{{ $booking->tenant_name }}</span>
                                    <span class="text-slate-500 font-medium">{{ $booking->tenant_phone }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $booking->occupants_count }} Penghuni</span>
                                </td>

                                <!-- Kontrakan -->
                                <td class="py-4 px-6">
                                    <a href="{{ route('properties.show', $booking->property->slug) }}" class="font-bold text-slate-800 hover:text-brand-600 block line-clamp-1">
                                        {{ $booking->property->title }}
                                    </a>
                                    <span class="text-[11px] text-slate-500">{{ $booking->property->city }}</span>
                                </td>

                                <!-- Durasi & Tanggal -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800">{{ $booking->duration_months }} Bulan</span>
                                    <span class="block text-[10px] text-slate-500 mt-0.5">
                                        {{ $booking->start_date->format('d/m/y') }} - {{ $booking->end_date->format('d/m/y') }}
                                    </span>
                                </td>

                                <!-- Total -->
                                <td class="py-4 px-6 font-extrabold text-brand-600 text-sm whitespace-nowrap">
                                    {{ $booking->formatted_grand_total }}
                                </td>

                                <!-- Bukti Transfer -->
                                <td class="py-4 px-6">
                                    @if($booking->payment_proof)
                                        <a href="{{ $booking->payment_proof }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-[10px] border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Belum diunggah</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $booking->status_badge_class }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right space-y-1 sm:space-y-0 sm:space-x-1 whitespace-nowrap">
                                    <!-- Verifikasi Pembayaran jika bukti sudah upload dan status bayar != verified -->
                                    @if($booking->payment_status !== 'verified')
                                        <form action="{{ route('owner.bookings.verify-payment', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-[11px] rounded-lg shadow-2xs" title="Verifikasi Pembayaran & Aktifkan Unit">
                                                Lunas
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Setujui jika status pending -->
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('owner.bookings.confirm', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-lg shadow-2xs">
                                                Setujui
                                            </button>
                                        </form>

                                        <button type="button" @click="rejectModalOpen = true; rejectBookingId = '{{ $booking->id }}'; rejectActionUrl = '{{ route('owner.bookings.reject', $booking->id) }}'" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg border border-rose-200">
                                            Tolak
                                        </button>
                                    @endif

                                    <!-- Selesaikan Masa Sewa jika status active -->
                                    @if($booking->status === 'active')
                                        <form action="{{ route('owner.bookings.complete', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah masa sewa kontrakan ini sudah selesai dan kunci sudah dikembalikan?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] rounded-lg shadow-2xs">
                                                Selesaikan Sewa
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('bookings.show', $booking->booking_code) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg inline-block">
                                        Faktur
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Reason Modal -->
    <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.outside="rejectModalOpen = false">
            <h3 class="text-base font-bold text-slate-900">Alasan Penolakan Booking</h3>
            <p class="text-xs text-slate-500">Tuliskan alasan mengapa permohonan booking ini tidak dapat disetujui (akan dibaca oleh calon penyewa):</p>
            
            <form :action="rejectActionUrl" method="POST" class="space-y-4">
                @csrf
                <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Unit kontrakan sudah dibooking secara offline terlebih dahulu..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm">
                        Tolak Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
