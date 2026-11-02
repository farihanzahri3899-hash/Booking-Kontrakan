@extends('layouts.app')

@section('title', 'Edit Kontrakan - ' . $property->title)

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('owner.dashboard') }}" class="hover:text-brand-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('owner.properties.index') }}" class="hover:text-brand-600">Kelola Kontrakan</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Edit: {{ $property->title }}</span>
        </nav>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Data Kontrakan</h1>
        <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi, harga sewa, atau status ketersediaan kontrakan Anda.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form action="{{ route('owner.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Informasi Utama -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">1. Informasi Dasar Properti</h3>
                
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $property->is_available) ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Tersedia untuk Disewa</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $property->is_featured) ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Unggulan</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama / Judul Kontrakan</label>
                <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="property_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Kontrakan</label>
                    <select name="property_type" id="property_type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="rumah" {{ old('property_type', $property->property_type) === 'rumah' ? 'selected' : '' }}>Rumah Kontrakan</option>
                        <option value="petakan" {{ old('property_type', $property->property_type) === 'petakan' ? 'selected' : '' }}>Kontrakan Petakan</option>
                        <option value="paviliun" {{ old('property_type', $property->property_type) === 'paviliun' ? 'selected' : '' }}>Paviliun Mandiri</option>
                        <option value="kost" {{ old('property_type', $property->property_type) === 'kost' ? 'selected' : '' }}>Kost Eksklusif</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap</label>
                <textarea name="description" id="description" rows="4" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('description', $property->description) }}</textarea>
            </div>
        </div>

        <!-- Harga & Deposit -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Harga & Biaya Sewa</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price_per_month" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Sewa / Bulan (Rp)</label>
                    <input type="number" name="price_per_month" id="price_per_month" value="{{ old('price_per_month', $property->price_per_month) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="price_per_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Sewa / Tahun (Opsional)</label>
                    <input type="number" name="price_per_year" id="price_per_year" value="{{ old('price_per_year', $property->price_per_year) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="deposit_amount" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deposit Jaminan (Rp)</label>
                    <input type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount', $property->deposit_amount) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Lokasi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">3. Lokasi & Alamat</h3>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Jalan & Nomor</label>
                <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kota / Kabupaten</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="district" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kecamatan</label>
                    <input type="text" name="district" id="district" value="{{ old('district', $property->district) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="postal_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $property->postal_code) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Spesifikasi Unit -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">4. Spesifikasi Fisik Unit</h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label for="bedrooms" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kamar Tidur</label>
                    <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="1" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="bathrooms" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kamar Mandi</label>
                    <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="1" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="building_size" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Luas Bangunan (m²)</label>
                    <input type="number" name="building_size" id="building_size" value="{{ old('building_size', $property->building_size) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="land_size" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Luas Tanah (m²)</label>
                    <input type="number" name="land_size" id="land_size" value="{{ old('land_size', $property->land_size) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="electricity_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Listrik</label>
                    <select name="electricity_type" id="electricity_type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="token" {{ old('electricity_type', $property->electricity_type) === 'token' ? 'selected' : '' }}>Token Prabayar (Masing-masing)</option>
                        <option value="pascabayar" {{ old('electricity_type', $property->electricity_type) === 'pascabayar' ? 'selected' : '' }}>Pascabayar (Bulanan)</option>
                        <option value="gratis" {{ old('electricity_type', $property->electricity_type) === 'gratis' ? 'selected' : '' }}>Termasuk dalam Uang Sewa</option>
                    </select>
                </div>

                <div>
                    <label for="water_source" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sumber Air</label>
                    <select name="water_source" id="water_source" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="sumur_bor" {{ old('water_source', $property->water_source) === 'sumur_bor' ? 'selected' : '' }}>Sumur Bor / Jetpump</option>
                        <option value="pam" {{ old('water_source', $property->water_source) === 'pam' ? 'selected' : '' }}>PDAM / PAM</option>
                        <option value="gratis" {{ old('water_source', $property->water_source) === 'gratis' ? 'selected' : '' }}>Gratis / Termasuk Sewa</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Fasilitas & Aturan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">5. Fasilitas & Tata Tertib</h3>

            @php
                $currentFacilities = old('facilities', $property->facilities ?? []);
            @endphp
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Fasilitas Tersedia</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($availableFacilities as $facility)
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer text-xs font-medium text-slate-700">
                            <input type="checkbox" name="facilities[]" value="{{ $facility }}" {{ is_array($currentFacilities) && in_array($facility, $currentFacilities) ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                            <span>{{ $facility }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <label for="rules" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Aturan Sewa (1 aturan per baris)</label>
                <textarea name="rules" id="rules" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('rules', is_array($property->rules) ? implode("\n", $property->rules) : '') }}</textarea>
            </div>
        </div>

        <!-- Foto Utama Properti -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">6. Foto Utama Properti</h3>

            <div class="flex items-center gap-4 mb-2">
                <img src="{{ $property->main_image }}" alt="Current Photo" class="w-20 h-20 rounded-xl object-cover border border-slate-200">
                <span class="text-xs text-slate-500">Foto saat ini</span>
            </div>

            <div>
                <label for="main_image_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ganti URL Foto (Opsional)</label>
                <input type="url" name="main_image_url" id="main_image_url" value="{{ old('main_image_url', $property->main_image) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
            </div>

            <div>
                <label for="main_image_file" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Atau Upload Gambar Baru</label>
                <input type="file" name="main_image_file" id="main_image_file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('owner.properties.index') }}" class="px-5 py-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all hover:scale-105">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
