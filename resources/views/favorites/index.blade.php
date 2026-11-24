@extends('layouts.app')

@section('title', 'Daftar Favorit & Bandingkan Hunian')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ viewMode: 'cards' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <svg class="w-3.5 h-3.5 fill-current text-rose-500" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    Wishlist Pilihan
                </span>
                <span class="text-xs text-slate-600">({{ $favorites->total() }} disimpan)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Kontrakan & Kost Favorit Saya
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Simpan beberapa hunian pilihan untuk membandingkan harga, luas, dan fasilitas secara langsung.
            </p>
        </div>

        @if($properties->isNotEmpty())
            <!-- Switcher: Kartu vs Tabel Komparasi -->
            <div class="inline-flex items-center p-1 bg-slate-200/80 rounded-xl self-start sm:self-auto">
                <button type="button" @click="viewMode = 'cards'" :class="viewMode === 'cards' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Tampilan Kartu
                </button>
                <button type="button" @click="viewMode = 'compare'" :class="viewMode === 'compare' ? 'bg-white text-brand-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Bandingkan Fasilitas & Harga
                </button>
            </div>
        @endif
    </div>

    @if($properties->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-16 text-center max-w-xl mx-auto shadow-sm">
            <div class="w-20 h-20 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Kontrakan Favorit</h3>
            <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                Anda belum menandai kontrakan atau kost manapun. Klik ikon hati pada properti yang Anda sukai untuk menyimpannya di sini.
            </p>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-600 text-white font-bold text-sm hover:bg-brand-700 shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Jelajah Kontrakan Sekarang
            </a>
        </div>
    @else
        <!-- Mode 1: Cards View -->
        <div x-show="viewMode === 'cards'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($properties as $property)
                    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group relative">
                        <!-- Image & Badges -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                            <img src="{{ $property->main_image ?? 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&auto=format&fit=crop&q=80' }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Remove from Favorites button -->
                            <form action="{{ route('favorites.toggle', $property) }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                <button type="submit" class="w-9 h-9 rounded-full bg-white/95 text-rose-500 shadow-md flex items-center justify-center hover:bg-white hover:scale-110 transition-all" title="Hapus dari favorit">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                </button>
                            </form>

                            <!-- Property Type Badge -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold text-white bg-slate-900/80 backdrop-blur">
                                    {{ match($property->property_type) {
                                        'rumah' => 'Rumah Kontrakan',
                                        'kost' => 'Kost Eksklusif',
                                        'petakan' => 'Kontrakan Petakan',
                                        'paviliun' => 'Paviliun Mandiri',
                                        default => 'Kontrakan'
                                    } }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center justify-between text-xs text-slate-600 mb-1.5">
                                <span class="flex items-center gap-1 text-slate-600 font-medium truncate">
                                    <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $property->city }}
                                </span>
                                <span class="flex items-center gap-1 font-bold text-amber-500">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($property->average_rating, 1) }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1 mb-2">
                                <a href="{{ route('properties.show', $property->slug) }}">{{ $property->title }}</a>
                            </h3>

                            <!-- Specs -->
                            <div class="flex items-center gap-3 text-xs text-slate-600 mb-4 pb-3 border-b border-slate-100">
                                <span class="flex items-center gap-1">🛏️ {{ $property->bedrooms }} KT</span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">🚿 {{ $property->bathrooms }} KM</span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">📐 {{ $property->building_size }} m²</span>
                            </div>

                            <!-- Price & Action -->
                            <div class="mt-auto flex items-center justify-between pt-2">
                                <div>
                                    <p class="text-[11px] text-slate-600">Sewa Mulai</p>
                                    <p class="text-base font-extrabold text-brand-700 leading-tight">
                                        {{ $property->formatted_price_month }}<span class="text-xs font-normal text-slate-600">/bln</span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('bookings.create', $property->slug) }}" class="px-3.5 py-2 text-xs font-bold rounded-xl text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition-colors">
                                        Booking
                                    </a>
                                    <a href="{{ route('properties.show', $property->slug) }}" class="p-2 text-slate-600 hover:text-brand-600 hover:bg-slate-100 rounded-xl transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        </div>

        <!-- Mode 2: Side-by-Side Comparison Table -->
        <div x-show="viewMode === 'compare'" x-cloak x-transition class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Tabel Komparasi Hunian</h2>
                    <p class="text-xs text-slate-600">Bandingkan spesifikasi dan fasilitas utama kontrakan yang Anda favoritkan.</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-brand-50 text-brand-700 rounded-lg border border-brand-200">
                    {{ $properties->count() }} Properti Dibandingkan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-slate-100">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-600 uppercase">
                        <tr>
                            <th scope="col" class="py-4 px-5 min-w-[200px]">Parameter</th>
                            @foreach($properties as $property)
                                <th scope="col" class="py-4 px-5 min-w-[260px] max-w-[300px]">
                                    <div class="flex items-center gap-2 mb-2">
                                        <img src="{{ $property->main_image ?? 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=200&auto=format&fit=crop&q=80' }}" class="w-10 h-10 rounded-lg object-cover">
                                        <div class="truncate">
                                            <a href="{{ route('properties.show', $property->slug) }}" class="font-bold text-slate-900 hover:text-brand-600 truncate block">
                                                {{ $property->title }}
                                            </a>
                                            <span class="text-[10px] text-slate-600 font-medium">{{ $property->city }}</span>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <!-- Harga per Bulan -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Harga Sewa Bulanan</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5 font-extrabold text-brand-700">
                                    {{ $property->formatted_price_month }} /bln
                                </td>
                            @endforeach
                        </tr>

                        <!-- Harga per Tahun -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Harga Sewa Tahunan</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5 text-slate-600">
                                    {{ $property->formatted_price_year ?? 'Hanya Sewa Bulanan' }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Tipe Properti -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Kategori Hunian</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ match($property->property_type) {
                                            'rumah' => 'Rumah Kontrakan',
                                            'kost' => 'Kost Eksklusif',
                                            'petakan' => 'Kontrakan Petakan',
                                            'paviliun' => 'Paviliun Mandiri',
                                            default => 'Kontrakan'
                                        } }}
                                    </span>
                                </td>
                            @endforeach
                        </tr>

                        <!-- Kamar Tidur & Mandi -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Kamar Tidur / Mandi</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5">
                                    {{ $property->bedrooms }} Kamar Tidur &bull; {{ $property->bathrooms }} Kamar Mandi
                                </td>
                            @endforeach
                        </tr>

                        <!-- Luas Bangunan & Tanah -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Luas Bangunan / Tanah</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5">
                                    {{ $property->building_size }} m² / {{ $property->land_size }} m²
                                </td>
                            @endforeach
                        </tr>

                        <!-- Deposit -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Uang Deposit</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5">
                                    {{ $property->deposit_amount ? 'Rp ' . number_format($property->deposit_amount, 0, ',', '.') : 'Tanpa Deposit' }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Fasilitas Utama -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Fasilitas Utama</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($property->facilities ?? [] as $fac)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[11px] bg-slate-100 text-slate-700">
                                                {{ $fac }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-600">-</span>
                                        @endforelse
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        <!-- Pemilik & Status Verifikasi -->
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900 bg-slate-50/30">Pemilik Hunian</td>
                            @foreach($properties as $property)
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-semibold text-slate-900 text-xs">{{ $property->user->name ?? 'Pemilik' }}</span>
                                        @if($property->user && $property->user->isVerified())
                                            <svg class="w-3.5 h-3.5 text-sky-500 fill-current" viewBox="0 0 20 20" title="Pemilik Terverifikasi (Centang Biru)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        <!-- Aksi -->
                        <tr class="bg-slate-50/50">
                            <td class="py-4 px-5 font-bold text-slate-900">Aksi</td>
                            @foreach($properties as $property)
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('bookings.create', $property->slug) }}" class="flex-1 text-center py-2 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition-colors">
                                            Booking
                                        </a>
                                        <a href="{{ route('properties.show', $property->slug) }}" class="py-2 px-3 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-colors">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
