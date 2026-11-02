@extends('layouts.app')

@section('title', 'Formulir Pengajuan Sewa - ' . $property->title)

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Konfirmasi & Pengajuan Sewa</h1>
        <p class="text-xs text-slate-500 mt-1">Lengkapi data diri dan tentukan durasi sewa kontrakan yang Anda inginkan.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    duration: 1,
    monthlyPrice: {{ $property->price_per_month }},
    yearlyPrice: {{ $property->price_per_year ?? ($property->price_per_month * 12) }},
    deposit: {{ $property->deposit_amount }},
    get totalRent() {
        if (this.duration >= 12 && {{ $property->price_per_year ? 'true' : 'false' }}) {
            return Math.floor(this.yearlyPrice * (this.duration / 12));
        }
        return this.monthlyPrice * this.duration;
    },
    get grandTotal() {
        return this.totalRent + this.deposit;
    },
    formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }
}">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Form Left (7 Cols) -->
        <div class="md:col-span-7 space-y-6">
            <form action="{{ route('bookings.store', $property->slug) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Property Summary Card -->
                <div class="p-4 rounded-2xl bg-white border border-slate-200 flex items-center gap-4">
                    <img src="{{ $property->main_image }}" alt="{{ $property->title }}" class="w-20 h-20 rounded-xl object-cover shrink-0">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700">{{ $property->property_type_label }}</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-1 line-clamp-1">{{ $property->title }}</h3>
                        <p class="text-xs text-slate-500">{{ $property->city }} &bull; {{ $property->bedrooms }} KT, {{ $property->bathrooms }} KM</p>
                    </div>
                </div>

                <!-- Rental Dates & Duration -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">1. Jadwal & Durasi Sewa</h3>

                    <div>
                        <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Menempati</label>
                        <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Durasi Sewa</label>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" @click="duration = 1" :class="duration === 1 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700'" class="py-2 text-center text-xs border rounded-xl">1 Bln</button>
                            <button type="button" @click="duration = 3" :class="duration === 3 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700'" class="py-2 text-center text-xs border rounded-xl">3 Bln</button>
                            <button type="button" @click="duration = 6" :class="duration === 6 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700'" class="py-2 text-center text-xs border rounded-xl">6 Bln</button>
                            <button type="button" @click="duration = 12" :class="duration === 12 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700'" class="py-2 text-center text-xs border rounded-xl">12 Bln</button>
                        </div>
                        <input type="hidden" name="duration_months" :value="duration">
                    </div>

                    <div>
                        <label for="occupants_count" class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Calon Penghuni</label>
                        <select name="occupants_count" id="occupants_count" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                            <option value="3">3 Orang</option>
                            <option value="4">4 Orang (Keluarga)</option>
                        </select>
                    </div>
                </div>

                <!-- Tenant Data -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Data Diri Pemesan</h3>

                    <div>
                        <label for="tenant_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Sesuai KTP</label>
                        <input type="text" name="tenant_name" id="tenant_name" value="{{ $user->name ?? '' }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="tenant_phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp Aktif</label>
                        <input type="text" name="tenant_phone" id="tenant_phone" value="{{ $user->phone ?? '' }}" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="tenant_id_card" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor KTP / NIK (Opsional)</label>
                        <input type="text" name="tenant_id_card" id="tenant_id_card" placeholder="16 digit NIK KTP Anda" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan untuk Pemilik (Opsional)</label>
                        <textarea name="notes" id="notes" rows="2" placeholder="Contoh: bawa motor 1, rencana masuk jam 10 pagi..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">3. Metode Pembayaran</h3>

                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="transfer_bca" checked class="text-brand-600 focus:ring-brand-500">
                            <div class="flex-1 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">Transfer Bank BCA</span>
                                <span class="text-[11px] text-slate-400 font-mono">BCA Virtual / Rekening</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="transfer_mandiri" class="text-brand-600 focus:ring-brand-500">
                            <div class="flex-1 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">Transfer Bank Mandiri</span>
                                <span class="text-[11px] text-slate-400 font-mono">Mandiri Rekening</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="qris" class="text-brand-600 focus:ring-brand-500">
                            <div class="flex-1 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">QRIS (Gopay / OVO / ShopeePay / Bank)</span>
                                <span class="text-[11px] text-slate-400">Scan Instan</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="tunai" class="text-brand-600 focus:ring-brand-500">
                            <div class="flex-1 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">Bayar Tunai di Tempat</span>
                                <span class="text-[11px] text-slate-400">Saat Serah Terima Kunci</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.01]">
                    Konfirmasi & Ajukan Booking
                </button>
            </form>
        </div>

        <!-- Price Summary Right (5 Cols) -->
        <div class="md:col-span-5">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-28 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Ringkasan Biaya</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Sewa (<span x-text="duration"></span> Bulan)</span>
                        <span class="font-bold text-slate-900" x-text="formatRupiah(totalRent)"></span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <div>
                            <span>Deposit Jaminan</span>
                            <span class="block text-[10px] text-slate-400">Dikembalikan saat masa sewa selesai</span>
                        </div>
                        <span class="font-bold text-slate-900" x-text="formatRupiah(deposit)"></span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between text-sm font-extrabold text-slate-900">
                        <span>Total yang Harus Dibayar</span>
                        <span class="text-brand-600 text-base" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-500 space-y-1.5 leading-relaxed">
                        <p class="font-bold text-slate-700">Ketentuan Booking:</p>
                        <p>&bull; Permohonan akan diverifikasi oleh pemilik properti dalam waktu maks. 24 jam.</p>
                        <p>&bull; Bukti transfer pembayaran diunggah setelah formulir ini dikirimkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
