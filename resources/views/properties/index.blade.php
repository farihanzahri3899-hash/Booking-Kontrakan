@extends('layouts.app')

@section('title', 'Cari & Jelajahi Kontrakan Tersedia')

@section('content')
<div class="bg-white border-b border-slate-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Jelajah Kontrakan</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Kontrakan Tersedia</h1>
                <p class="text-sm text-slate-500 mt-1">Menampilkan {{ $properties->total() }} pilihan kontrakan siap huni di berbagai lokasi.</p>
            </div>
            
            @if(array_filter($filters))
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors w-fit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Reset Semua Filter</span>
                </a>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ mobileFilterOpen: false }">
    <!-- Mobile Filter Toggle Button -->
    <div class="lg:hidden mb-6 flex items-center justify-between">
        <button @click="mobileFilterOpen = !mobileFilterOpen" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 shadow-sm">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            <span>Filter Pencarian</span>
            @if(array_filter($filters))
                <span class="w-2 h-2 rounded-full bg-brand-600"></span>
            @endif
        </button>

        <span class="text-xs font-semibold text-slate-500">{{ $properties->total() }} Kontrakan Ditemukan</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1" :class="mobileFilterOpen ? 'block' : 'hidden lg:block'">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sticky top-28 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter Kontrakan
                    </h3>
                    @if(array_filter($filters))
                        <a href="{{ route('properties.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">Reset</a>
                    @endif
                </div>

                <form action="{{ route('properties.index') }}" method="GET" class="space-y-5">
                    <!-- Keyword Search -->
                    <div>
                        <label for="filter_q" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Kunci</label>
                        <input type="text" name="q" id="filter_q" value="{{ request('q') }}" placeholder="Nama, jalan, area..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label for="filter_city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kota / Lokasi</label>
                        <select name="city" id="filter_city" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none text-slate-700">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Property Type -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Properti</label>
                        <div class="space-y-1.5">
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                                <span>Semua Tipe</span>
                            </label>
                            @foreach($types as $key => $label)
                                <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="type" value="{{ $key }}" {{ request('type') === $key ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Sewa / Bulan (Rp)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        </div>
                    </div>

                    <!-- Bedrooms -->
                    <div>
                        <label for="filter_bedrooms" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Minimal Kamar Tidur</label>
                        <select name="bedrooms" id="filter_bedrooms" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none text-slate-700">
                            <option value="">Berapa Saja</option>
                            <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+ Kamar</option>
                            <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+ Kamar</option>
                            <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+ Kamar</option>
                        </select>
                    </div>

                    <!-- Urutan / Sorting -->
                    <div>
                        <label for="filter_sort" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutkan</label>
                        <select name="sort" id="filter_sort" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none text-slate-700">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Paling Baru Ditambahkan</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Property Catalog Grid -->
        <div class="lg:col-span-3">
            @if($properties->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Tidak Ada Kontrakan yang Cocok</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                        Kami tidak menemukan unit kontrakan dengan filter yang Anda tentukan. Coba ubah kata kunci atau hapus filter harga.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Lihat Semua Kontrakan
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($properties as $property)
                        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 hover:border-brand-500/60 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                            <!-- Image & Badges -->
                            <div class="relative h-52 overflow-hidden bg-slate-100">
                                <img src="{{ $property->main_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-white/95 backdrop-blur text-slate-800 shadow-sm">
                                        {{ $property->property_type_label }}
                                    </span>
                                </div>

                                <div class="absolute bottom-3 right-3">
                                    @if($property->is_available)
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/90 backdrop-blur text-white shadow-sm">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/90 backdrop-blur text-white shadow-sm">
                                            Tersewa
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- City & Rating -->
                                    <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                        <span class="flex items-center gap-1 text-slate-600 font-medium">
                                            <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $property->city }}
                                        </span>
                                        <div class="flex items-center gap-1 text-amber-500 font-bold">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span>{{ $property->average_rating }}</span>
                                            <span class="text-slate-400 font-normal">({{ $property->reviews_count }})</span>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1">
                                        <a href="{{ route('properties.show', $property->slug) }}">
                                            {{ $property->title }}
                                        </a>
                                    </h3>

                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $property->description }}
                                    </p>

                                    <!-- Specs -->
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-3 text-xs font-semibold text-slate-600">
                                        <div class="flex items-center gap-1" title="Kamar Tidur">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            <span>{{ $property->bedrooms }} KT</span>
                                        </div>
                                        <div class="flex items-center gap-1" title="Kamar Mandi">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>{{ $property->bathrooms }} KM</span>
                                        </div>
                                        @if($property->building_size)
                                            <div class="flex items-center gap-1" title="Luas Bangunan">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                                <span>{{ $property->building_size }} m²</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price & Action -->
                                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="block text-[10px] font-medium text-slate-400">Harga Sewa</span>
                                        <span class="text-sm font-extrabold text-brand-600">
                                            {{ $property->formatted_price_month }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">/bln</span>
                                    </div>

                                    <a href="{{ route('properties.show', $property->slug) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-slate-900 hover:bg-brand-600 transition-colors shadow-sm">
                                        <span>Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
