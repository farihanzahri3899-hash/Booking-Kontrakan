@extends('layouts.app')

@section('title', 'Cari & Booking Kontrakan Impianmu Tanpa Ribet')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-b from-brand-900 via-brand-800 to-slate-900 text-white overflow-hidden py-16 sm:py-24">
        <!-- Background Pattern Decor -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-5">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/15 text-xs font-semibold text-brand-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Platform Sewa Kontrakan #1 Terpercaya
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                    Temukan Kontrakan Nyaman <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-teal-200 to-emerald-400">
                        Sesuai Budget & Lokasi Anda
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    Mulai dari rumah keluarga, paviliun mandiri, hingga petakan minimalis. Terverifikasi langsung tanpa calo dan bebas biaya admin tersembunyi.
                </p>
            </div>

            <!-- Modern Search Box Card -->
            <div class="mt-10 max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-4 sm:p-5 text-slate-800 border border-slate-100">
                <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-center">
                    <!-- Keyword / Nama -->
                    <div class="sm:col-span-4">
                        <label for="q" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari Kontrakan / Area
                        </label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Contoh: Gandaria, Pasteur, Margonda..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all placeholder:text-slate-400">
                    </div>

                    <!-- Kota -->
                    <div class="sm:col-span-3">
                        <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Pilih Kota
                        </label>
                        <select name="city" id="city" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all text-slate-700">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $cityItem)
                                <option value="{{ $cityItem->city }}" {{ request('city') === $cityItem->city ? 'selected' : '' }}>
                                    {{ $cityItem->city }} ({{ $cityItem->total }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipe Kontrakan -->
                    <div class="sm:col-span-3">
                        <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Tipe Sewa
                        </label>
                        <select name="type" id="type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all text-slate-700">
                            <option value="">Semua Tipe</option>
                            <option value="rumah" {{ request('type') === 'rumah' ? 'selected' : '' }}>Rumah Kontrakan</option>
                            <option value="petakan" {{ request('type') === 'petakan' ? 'selected' : '' }}>Kontrakan Petakan</option>
                            <option value="paviliun" {{ request('type') === 'paviliun' ? 'selected' : '' }}>Paviliun Mandiri</option>
                            <option value="kost" {{ request('type') === 'kost' ? 'selected' : '' }}>Kost Eksklusif</option>
                        </select>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="sm:col-span-2 pt-2 sm:pt-6">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold rounded-xl shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Cari</span>
                        </button>
                    </div>
                </form>

                <!-- Quick tags under search -->
                <div class="mt-3.5 pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                    <span class="font-semibold text-slate-700">Paling Dicari:</span>
                    <a href="{{ route('properties.index', ['city' => 'Jakarta Selatan']) }}" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">Jakarta Selatan</a>
                    <a href="{{ route('properties.index', ['city' => 'Bandung']) }}" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">Bandung</a>
                    <a href="{{ route('properties.index', ['city' => 'Yogyakarta']) }}" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">Yogyakarta (UGM)</a>
                    <a href="{{ route('properties.index', ['type' => 'petakan']) }}" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">Petakan 3 Sekat</a>
                    <a href="{{ route('properties.index', ['max_price' => 2000000]) }}" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">&le; Rp 2 Jt/bln</a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-12 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-4 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                    <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $stats['properties_count'] }}+</p>
                    <p class="text-xs text-slate-300 mt-1">Total Unit Kontrakan</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                    <p class="text-2xl sm:text-3xl font-extrabold text-brand-300">{{ $stats['available_count'] }}</p>
                    <p class="text-xs text-slate-300 mt-1">Unit Siap Dihuni</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                    <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $stats['tenants_count'] }}+</p>
                    <p class="text-xs text-slate-300 mt-1">Penyewa Terdaftar</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                    <p class="text-2xl sm:text-3xl font-extrabold text-emerald-400">100%</p>
                    <p class="text-xs text-slate-300 mt-1">Terverifikasi Bebas Calo</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Section -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">Kategori Pilihan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Pilih Tipe Hunian Idaman Anda</h2>
            </div>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-600 hover:text-brand-700">
                <span>Lihat Semua Kontrakan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Tipe 1: Rumah Keluarga -->
            <a href="{{ route('properties.index', ['type' => 'rumah']) }}" class="group relative rounded-2xl p-6 bg-white border border-slate-200/90 hover:border-brand-500 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Rumah Kontrakan</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">2-3 Kamar tidur, halaman atau carport, cocok untuk hunian keluarga.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                    <span>Eksplor Rumah &rarr;</span>
                </div>
            </a>

            <!-- Tipe 2: Petakan Minimalis -->
            <a href="{{ route('properties.index', ['type' => 'petakan']) }}" class="group relative rounded-2xl p-6 bg-white border border-slate-200/90 hover:border-brand-500 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Kontrakan Petakan</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Model 3 sekat praktis, kamar mandi dalam, harga sangat terjangkau.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition-transform">
                    <span>Eksplor Petakan &rarr;</span>
                </div>
            </a>

            <!-- Tipe 3: Paviliun Mandiri -->
            <a href="{{ route('properties.index', ['type' => 'paviliun']) }}" class="group relative rounded-2xl p-6 bg-white border border-slate-200/90 hover:border-brand-500 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Paviliun Mandiri</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Privat dengan pintu sendiri, teras santai, dapur lengkap, dan AC.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-purple-600 group-hover:translate-x-1 transition-transform">
                    <span>Eksplor Paviliun &rarr;</span>
                </div>
            </a>

            <!-- Tipe 4: Kost Eksklusif -->
            <a href="{{ route('properties.index', ['type' => 'kost']) }}" class="group relative rounded-2xl p-6 bg-white border border-slate-200/90 hover:border-brand-500 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Kost Eksklusif</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Fasilitas kamar lengkap, WiFi kencang, dekat perkantoran & kampus.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-amber-600 group-hover:translate-x-1 transition-transform">
                    <span>Eksplor Kost &rarr;</span>
                </div>
            </a>
        </div>
    </section>

    <!-- Featured Properties Section -->
    <section class="py-12 bg-slate-100/70 border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">Paling Diminati</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Kontrakan Pilihan Unggulan</h2>
                    <p class="text-sm text-slate-500 mt-1">Unit rekomendasi dengan ulasan terbaik, fasilitas lengkap, dan lokasi sangat strategis.</p>
                </div>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-sm font-bold text-slate-700 hover:text-brand-600 border border-slate-200 rounded-xl shadow-sm hover:shadow transition-all">
                    <span>Lihat Semua ({{ $stats['properties_count'] }})</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredProperties as $property)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 hover:border-brand-500/60 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                        <!-- Image & Badges -->
                        <div class="relative h-56 overflow-hidden bg-slate-100">
                            <img src="{{ $property->main_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-white/90 backdrop-blur text-slate-800 shadow-sm">
                                    {{ $property->property_type_label }}
                                </span>
                                @if($property->is_featured)
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-500 text-white shadow-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Unggulan
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-3 right-3">
                                @if($property->is_available)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/90 backdrop-blur text-white shadow-sm">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500/90 backdrop-blur text-white shadow-sm">
                                        Penuh / Tersewa
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Location & Rating -->
                                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                    <span class="flex items-center gap-1 text-slate-600 font-medium">
                                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $property->district ? $property->district . ', ' : '' }}{{ $property->city }}
                                    </span>
                                    <div class="flex items-center gap-1 text-amber-500 font-bold">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>{{ $property->average_rating }}</span>
                                        <span class="text-slate-400 font-normal">({{ $property->reviews_count }})</span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1">
                                    <a href="{{ route('properties.show', $property->slug) }}">
                                        {{ $property->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $property->description }}
                                </p>

                                <!-- Specs Pills -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-4 text-xs font-semibold text-slate-600">
                                    <div class="flex items-center gap-1.5" title="{{ $property->bedrooms }} Kamar Tidur">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span>{{ $property->bedrooms }} KT</span>
                                    </div>
                                    <div class="flex items-center gap-1.5" title="{{ $property->bathrooms }} Kamar Mandi">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>{{ $property->bathrooms }} KM</span>
                                    </div>
                                    @if($property->building_size)
                                        <div class="flex items-center gap-1.5" title="Luas {{ $property->building_size }} m2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                            <span>{{ $property->building_size }} m²</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Price & Button -->
                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-[11px] font-medium text-slate-400">Mulai dari</span>
                                    <span class="text-base font-extrabold text-brand-600">
                                        {{ $property->formatted_price_month }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-normal">/ bln</span>
                                </div>

                                <a href="{{ route('properties.show', $property->slug) }}" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-brand-600 transition-colors shadow-sm">
                                    <span>Lihat Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">Keunggulan Platform</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Sewa Kontrakan Jadi Jauh Lebih Mudah</h2>
            <p class="text-sm text-slate-500 mt-2">Kami merancang pengalaman menyewa rumah dan kontrakan yang aman, jelas, dan tanpa ribet.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Tanpa Calo & Perantara</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Terhubung langsung dengan pemilik properti asli. Bebas markup harga dari pihak ketiga dan transaksi jauh lebih transparan.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Rincian Biaya Transparan</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Kalkulasi biaya sewa, durasi bulan, hingga deposit jaminan tertera detail di awal tanpa ada biaya siluman saat check-in.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Booking & Invoice Instan</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Ajukan sewa langsung secara online, upload bukti bayar, dan dapatkan invoice digital sebagai tanda bukti sah sewa menyewa.
                </p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-400 bg-brand-500/10 px-3 py-1 rounded-full border border-brand-500/20">Ulasan Nyata</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-2">Apa Kata Mereka yang Sudah Menyewa?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($testimonials as $testi)
                    <div class="p-6 rounded-2xl bg-slate-800/90 border border-slate-700/80 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-3">
                                @for($i = 0; $i < $testi->rating; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-sm text-slate-300 italic leading-relaxed">
                                "{{ $testi->comment }}"
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-700/80 flex items-center gap-3">
                            <img src="{{ $testi->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($testi->user->name) . '&background=059669&color=fff' }}" alt="{{ $testi->user->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-600">
                            <div>
                                <p class="text-sm font-bold text-white">{{ $testi->user->name }}</p>
                                <p class="text-xs text-brand-400">{{ $testi->property->title }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Owner CTA Banner -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl bg-gradient-to-r from-brand-800 to-teal-700 p-8 sm:p-14 overflow-hidden shadow-2xl text-white flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="max-w-2xl space-y-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/15 backdrop-blur text-white">Khusus Pemilik Kontrakan</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Punya Rumah atau Petakan Kosong? <br>
                    Sewakan Sekarang & Temukan Penyewa Tepat!
                </h2>
                <p class="text-sm sm:text-base text-brand-100 leading-relaxed">
                    Kelola ketersediaan kontrakan, terima uang sewa tepat waktu, dan pantau calon penyewa lewat dashboard pemilik yang praktis.
                </p>
            </div>

            <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('register') }}" class="px-6 py-3.5 bg-white text-brand-800 hover:bg-slate-100 font-bold rounded-xl text-center shadow-lg transition-all hover:scale-105">
                    Daftar Sebagai Pemilik
                </a>
                <a href="{{ route('quick-login', 'pemilik') }}" class="px-6 py-3.5 bg-brand-900/40 hover:bg-brand-900/60 border border-white/20 text-white font-bold rounded-xl text-center backdrop-blur transition-all">
                    Demo Panel Pemilik
                </a>
            </div>
        </div>
    </section>
@endsection
