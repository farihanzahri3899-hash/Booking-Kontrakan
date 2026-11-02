@extends('layouts.app')

@section('title', 'Detail Booking ' . $booking->booking_code)

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('bookings.index') }}" class="hover:text-brand-600">Pesanan Saya</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">{{ $booking->booking_code }}</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span>Faktur Sewa #{{ $booking->booking_code }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $booking->status_badge_class }}">
                        {{ $booking->status_label }}
                    </span>
                </h1>
                <p class="text-xs text-slate-500 mt-1">Dibuat pada {{ $booking->created_at->isoFormat('D MMMM Y, HH:mm') ?? $booking->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->property->user->phone ?? '081289123456') }}?text={{ urlencode('Halo Bapak/Ibu, saya penyewa booking #' . $booking->booking_code . ' untuk kontrakan: ' . $booking->property->title) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Hubungi Pemilik</span>
                </a>

                @if($booking->status === 'pending' && auth()->id() === $booking->user_id)
                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                        @csrf
                        <button type="submit" class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition-colors">
                            Batalkan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Progress Timeline Stepper -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">Status Pengajuan Sewa</h3>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 relative">
            <!-- Step 1: Diajukan -->
            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-md shadow-emerald-500/20">
                    &check;
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">1. Booking Diajukan</p>
                    <p class="text-[11px] text-slate-400">Data sewa tersimpan</p>
                </div>
            </div>

            <!-- Step 2: Pembayaran -->
            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 {{ $booking->payment_status === 'verified' ? 'bg-emerald-600 text-white' : ($booking->payment_status === 'paid' ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-600') }}">
                    {{ $booking->payment_status === 'verified' ? '✓' : '2' }}
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">2. Pembayaran</p>
                    <p class="text-[11px] text-slate-400">{{ $booking->payment_status_label }}</p>
                </div>
            </div>

            <!-- Step 3: Verifikasi Pemilik -->
            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 {{ in_array($booking->status, ['confirmed', 'active', 'completed']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                    {{ in_array($booking->status, ['confirmed', 'active', 'completed']) ? '✓' : '3' }}
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">3. Konfirmasi Pemilik</p>
                    <p class="text-[11px] text-slate-400">Persetujuan kunci</p>
                </div>
            </div>

            <!-- Step 4: Menempati -->
            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 {{ in_array($booking->status, ['active', 'completed']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                    {{ in_array($booking->status, ['active', 'completed']) ? '✓' : '4' }}
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">4. Siap Dihuni</p>
                    <p class="text-[11px] text-slate-400">Masa sewa berjalan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Booking Summary & Invoice Details (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Property Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
                <img src="{{ $booking->property->main_image }}" alt="{{ $booking->property->title }}" class="w-24 h-24 rounded-xl object-cover shrink-0">
                <div class="flex-1 min-w-0">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700">
                        {{ $booking->property->property_type_label }}
                    </span>
                    <h3 class="text-base font-bold text-slate-900 truncate mt-1">
                        <a href="{{ route('properties.show', $booking->property->slug) }}" class="hover:text-brand-600">
                            {{ $booking->property->title }}
                        </a>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $booking->property->address }}, {{ $booking->property->city }}</p>
                    <p class="text-xs font-semibold text-brand-600 mt-1">{{ $booking->property->formatted_price_month }} / bulan</p>
                </div>
            </div>

            <!-- Rental Details Breakdown -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Sewa</h3>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block">Nama Penyewa</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $booking->tenant_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">No. WhatsApp</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $booking->tenant_phone }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">Tanggal Mulai Sewa</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $booking->start_date->format('d M Y') }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">Tanggal Selesai Sewa</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $booking->end_date->format('d M Y') }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">Durasi Kontrak</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $booking->duration_months }} Bulan</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">Jumlah Penghuni</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $booking->occupants_count }} Orang</span>
                    </div>
                </div>

                @if($booking->notes)
                    <div class="pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 block">Catatan Pemesan:</span>
                        <p class="text-slate-700 italic mt-0.5">{{ $booking->notes }}</p>
                    </div>
                @endif

                @if($booking->rejection_reason)
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                        <span class="font-bold block">Alasan Penolakan:</span>
                        <p class="mt-0.5">{{ $booking->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            <!-- Price Table -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-3">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Rincian Pembayaran</h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Sewa ({{ $booking->duration_months }} Bulan &times; Rp {{ number_format($booking->monthly_price, 0, ',', '.') }})</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($booking->total_rent_price, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>Deposit Jaminan (Dikembalikan di akhir)</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($booking->deposit_price, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between text-base font-extrabold text-slate-900">
                        <span>Total Tagihan</span>
                        <span class="text-brand-600">{{ $booking->formatted_grand_total }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Payment Instructions & Proof Upload (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Instruksi Pembayaran</h3>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $booking->payment_status_badge_class }}">
                        {{ $booking->payment_status_label }}
                    </span>
                </div>

                <!-- Rekening Box -->
                <div class="space-y-3 text-xs" x-data="{ copiedBca: false, copiedMandiri: false }">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-700">Bank Central Asia (BCA)</span>
                            <span class="text-[10px] text-slate-400">Transfer Manual / VA</span>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <span class="font-mono text-sm font-extrabold text-slate-900">8230129381</span>
                            <button type="button" @click="navigator.clipboard.writeText('8230129381'); copiedBca = true; setTimeout(() => copiedBca = false, 2000)" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] font-semibold text-slate-700 hover:bg-slate-100">
                                <span x-show="!copiedBca">Salin</span>
                                <span x-show="copiedBca" x-cloak class="text-emerald-600 font-bold">Tersalin!</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500">a.n PT Booking Kontrakan Indonesia</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-700">Bank Mandiri</span>
                            <span class="text-[10px] text-slate-400">Transfer Manual</span>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <span class="font-mono text-sm font-extrabold text-slate-900">1370019283719</span>
                            <button type="button" @click="navigator.clipboard.writeText('1370019283719'); copiedMandiri = true; setTimeout(() => copiedMandiri = false, 2000)" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] font-semibold text-slate-700 hover:bg-slate-100">
                                <span x-show="!copiedMandiri">Salin</span>
                                <span x-show="copiedMandiri" x-cloak class="text-emerald-600 font-bold">Tersalin!</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500">a.n PT Booking Kontrakan Indonesia</p>
                    </div>
                </div>

                <!-- Upload Bukti Bayar Section -->
                <div class="pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Bukti Pembayaran</h4>

                    @if($booking->payment_proof)
                        <div class="space-y-3">
                            <div class="h-44 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 relative group">
                                <img src="{{ $booking->payment_proof }}" alt="Bukti Transfer" class="w-full h-full object-cover">
                                <a href="{{ $booking->payment_proof }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold">
                                    Lihat Ukuran Penuh &rarr;
                                </a>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-emerald-700 bg-emerald-50 p-2.5 rounded-xl border border-emerald-200">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Bukti transfer telah berhasil dikirimkan.</span>
                            </div>
                        </div>
                    @else
                        @if(auth()->id() === $booking->user_id)
                            <form action="{{ route('bookings.upload-proof', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors">
                                    <svg class="w-8 h-8 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs font-semibold text-slate-700">Pilih file bukti transfer (JPG / PNG)</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Maksimal 3 MB</p>
                                    <input type="file" name="payment_proof" accept="image/*" class="mt-2 block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                                </div>
                                <button type="submit" class="w-full py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
