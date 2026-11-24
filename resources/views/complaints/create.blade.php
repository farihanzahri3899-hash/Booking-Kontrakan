@extends('layouts.app')

@section('title', 'Buat Laporan Kendala Kontrakan')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Back button -->
    <a href="{{ route('complaints.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-brand-600 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Laporan
    </a>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
        <div class="mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 mb-2">
                Formulir Komplain & Perbaikan
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Laporkan Kendala atau Kerusakan Fasilitas
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Sampaikan kendala fasilitas tempat tinggal Anda secara jelas agar pemilik dapat segera menindaklanjuti perbaikan.
            </p>
        </div>

        @if($bookings->isEmpty())
            <div class="p-6 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                <p class="font-bold mb-1">Perhatian</p>
                <p>Anda belum memiliki riwayat booking hunian terkonfirmasi. Laporan kendala ditujukan bagi penyewa yang telah memesan atau sedang menghuni unit kontrakan.</p>
                <a href="{{ route('properties.index') }}" class="inline-block mt-3 px-4 py-2 bg-amber-600 text-white rounded-xl text-xs font-bold hover:bg-amber-700">
                    Cari Kontrakan Terlebih Dahulu
                </a>
            </div>
        @else
            <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Pilih Kontrakan / Booking -->
                <div>
                    <label for="booking_id" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Pilih Unit Kontrakan yang Ditempati <span class="text-rose-500">*</span>
                    </label>
                    <select id="booking_id" name="booking_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900 bg-white" required onchange="updatePropertyId(this)">
                        <option value="">-- Pilih Unit Kontrakan --</option>
                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}" data-property-id="{{ $booking->property_id }}" {{ (old('booking_id') == $booking->id || $selectedBookingId == $booking->id) ? 'selected' : '' }}>
                                {{ $booking->property->title }} (Kode: {{ $booking->booking_code }}) - {{ $booking->property->city }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="property_id" name="property_id" value="{{ old('property_id', $selectedPropertyId ?? ($bookings->first()->property_id ?? '')) }}">
                    @error('property_id')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Kendala -->
                <div>
                    <label for="category" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Kategori Kendala <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" name="category" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900 bg-white" required>
                        <option value="fasilitas" {{ old('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas & Furnitur (AC, Ranjang, Pintu, Lemari)</option>
                        <option value="listrik_air" {{ old('category') == 'listrik_air' ? 'selected' : '' }}>Listrik & Air (Kran bocor, Lampu mati, Meteran error, Pompa)</option>
                        <option value="kebersihan" {{ old('category') == 'kebersihan' ? 'selected' : '' }}>Kebersihan & Sampah</option>
                        <option value="keamanan" {{ old('category') == 'keamanan' ? 'selected' : '' }}>Keamanan & Lingkungan Sekitar</option>
                        <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Laporan -->
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Judul Laporan Singkat <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: AC kamar utama tidak dingin hanya keluar angin" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900" required>
                    @error('title')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Rinci -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Uraian Detail Kendala <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4" placeholder="Jelaskan secara spesifik kerusakan yang terjadi, sejak kapan terjadi, dan tindakan darurat apa yang sudah Anda lakukan..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unggah Foto Bukti -->
                <div>
                    <label for="photo" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Unggah Foto Bukti Kerusakan (Opsional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-brand-400 transition-colors bg-slate-50/50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-xs text-slate-600 justify-center">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-bold text-brand-600 hover:text-brand-500 focus-within:outline-none">
                                    <span>Pilih file gambar</span>
                                    <input id="photo" name="photo" type="file" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">atau seret ke sini</p>
                            </div>
                            <p class="text-[11px] text-slate-600">PNG, JPG, JPEG maksimal 3MB</p>
                        </div>
                    </div>
                    @error('photo')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('complaints.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-700 shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                        Kirim Laporan ke Pemilik &rarr;
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
    function updatePropertyId(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const propId = selectedOption.getAttribute('data-property-id');
        if (propId) {
            document.getElementById('property_id').value = propId;
        }
    }
</script>
@endsection
