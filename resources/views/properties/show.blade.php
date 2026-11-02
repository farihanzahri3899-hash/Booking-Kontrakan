@extends('layouts.app')

@section('title', $property->title . ' - Kontrakan di ' . $property->city)

@section('content')
<div class="bg-white border-b border-slate-200 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('properties.index') }}" class="hover:text-brand-600">Jelajah Kontrakan</a>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate">{{ $property->title }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    activeImage: '{{ $property->main_image }}',
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
    <!-- Header Title & Badges -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                    {{ $property->property_type_label }}
                </span>
                @if($property->is_available)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Siap Dihuni / Tersedia
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        Sedang Tersewa / Penuh
                    </span>
                @endif
                @if($property->is_featured)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Pilihan Unggulan
                    </span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $property->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-slate-500 mt-2">
                <span class="flex items-center gap-1.5 text-slate-700 font-medium">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $property->address }}, {{ $property->district ? $property->district . ', ' : '' }}{{ $property->city }}
                </span>
                <span class="text-slate-300">&bull;</span>
                <div class="flex items-center gap-1.5 text-amber-500 font-bold">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>{{ $property->average_rating }}</span>
                    <span class="text-slate-500 font-normal">({{ $property->reviews_count }} ulasan penyewa)</span>
                </div>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->user->phone ?? '081289123456') }}?text={{ urlencode('Halo, saya tertarik dengan kontrakan: ' . $property->title . ' (' . url()->current() . ')') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                <span>Chat Pemilik</span>
            </a>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
        <!-- Main Big Image -->
        <div class="md:col-span-3 h-80 sm:h-[450px] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
            <img :src="activeImage" alt="{{ $property->title }}" class="w-full h-full object-cover transition-all duration-300">
        </div>

        <!-- Thumbnails Strip -->
        <div class="grid grid-cols-3 md:grid-cols-1 gap-3 h-auto md:h-[450px]">
            @php
                $allImages = $property->images && count($property->images) > 0 ? $property->images : [$property->main_image];
            @endphp
            @foreach(array_slice($allImages, 0, 3) as $img)
                <button type="button" @click="activeImage = '{{ $img }}'" class="h-24 md:h-[142px] rounded-xl overflow-hidden border-2 transition-all relative" :class="activeImage === '{{ $img }}' ? 'border-brand-600 ring-2 ring-brand-500/30' : 'border-slate-200 opacity-70 hover:opacity-100'">
                    <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left Content Area (8 Cols) -->
        <div class="lg:col-span-8 space-y-10">
            <!-- Key Specifications Banner -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm">
                <div class="text-center p-2">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Kamar Tidur</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-1">{{ $property->bedrooms }} Kamar</p>
                </div>
                <div class="text-center p-2 border-l border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Kamar Mandi</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-1">{{ $property->bathrooms }} KM</p>
                </div>
                <div class="text-center p-2 border-l border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Luas Bangunan</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-1">{{ $property->building_size ?? 36 }} m²</p>
                </div>
                <div class="text-center p-2 border-l border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Luas Tanah</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-1">{{ $property->land_size ?? 40 }} m²</p>
                </div>
                <div class="text-center p-2 border-l border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Listrik</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-1 capitalize">{{ $property->electricity_type }}</p>
                </div>
                <div class="text-center p-2 border-l border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Sumber Air</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-1 uppercase">{{ str_replace('_', ' ', $property->water_source) }}</p>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Deskripsi Lengkap
                </h2>
                <div class="text-sm text-slate-600 leading-relaxed space-y-4 whitespace-pre-line">
                    {{ $property->description }}
                </div>
            </div>

            <!-- Facilities -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Fasilitas Kontrakan
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @forelse($property->facilities ?? [] as $facility)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 text-sm font-semibold text-slate-700">
                            <div class="w-7 h-7 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>{{ $facility }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Tidak ada fasilitas khusus yang tertera.</p>
                    @endforelse
                </div>
            </div>

            <!-- House Rules -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tata Tertib & Aturan Sewa
                </h2>
                <ul class="space-y-3">
                    @forelse($property->rules ?? [] as $rule)
                        <li class="flex items-start gap-3 text-sm text-slate-700">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">&check;</span>
                            <span>{{ $rule }}</span>
                        </li>
                    @empty
                        <li class="text-xs text-slate-400">Menjaga ketertiban umum dan kebersihan kontrakan.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Owner Profile Card -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <img src="{{ $property->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($property->user->name) . '&background=059669&color=fff' }}" alt="{{ $property->user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-brand-500 shadow-sm">
                    <div>
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <h3 class="text-base font-bold text-slate-900">{{ $property->user->name }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                Pemilik Terverifikasi
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Mengelola unit kontrakan di {{ $property->city }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Respons cepat &bull; Ramah &bull; Terpercaya</p>
                    </div>
                </div>

                <div class="shrink-0 flex items-center gap-3">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->user->phone ?? '081289123456') }}?text={{ urlencode('Halo Bapak/Ibu ' . $property->user->name . ', saya ingin bertanya mengenai unit kontrakan: ' . $property->title) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all hover:scale-105">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>WhatsApp Pemilik</span>
                    </a>
                </div>
            </div>

            <!-- Reviews & Comments Section -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Ulasan Penyewa ({{ $property->reviews_count }})
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Nilai kepuasan rata-rata {{ $property->average_rating }} dari 5.0 bintang</p>
                    </div>
                </div>

                <!-- Review list -->
                <div class="divide-y divide-slate-100 mt-6">
                    @forelse($property->reviews as $review)
                        <div class="py-5 first:pt-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=059669&color=fff' }}" alt="{{ $review->user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $review->user->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-amber-500">
                                    @for($i = 0; $i < $review->rating; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-12">
                                {{ $review->comment }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada ulasan untuk kontrakan ini. Jadilah penyewa pertama yang memberikan ulasan!</p>
                    @endforelse
                </div>

                <!-- Add Review Box for Authenticated Tenant -->
                @auth
                    @if(auth()->user()->isTenant())
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-sm font-bold text-slate-900 mb-3">Tulis Ulasan Anda</h4>
                            <form action="{{ route('reviews.store', $property->slug) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Rating Kepuasan (1-5 Bintang)</label>
                                    <select name="rating" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 Bintang (Sangat Puas)</option>
                                        <option value="4">⭐⭐⭐⭐ 4 Bintang (Puas / Nyaman)</option>
                                        <option value="3">⭐⭐⭐ 3 Bintang (Cukup Baik)</option>
                                        <option value="2">⭐⭐ 2 Bintang (Kurang Puas)</option>
                                        <option value="1">⭐ 1 Bintang (Tidak Rekomen)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Pengalaman / Ulasan Anda</label>
                                    <textarea name="comment" rows="3" placeholder="Ceritakan bagaimana kebersihan, keramahan pemilik, dan kenyamanan lingkungan kontrakan..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Right Column: Interactive Booking Card (4 Cols) -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-6 sm:p-7 sticky top-28 space-y-6">
                <!-- Price Header -->
                <div class="pb-5 border-b border-slate-100">
                    <span class="text-xs font-semibold text-slate-400">Harga Sewa</span>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <span class="text-2xl sm:text-3xl font-extrabold text-brand-600">{{ $property->formatted_price_month }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ bulan</span>
                    </div>

                    @if($property->price_per_year)
                        <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                            <span>Sewa Tahunan: {{ $property->formatted_price_year }} (Hemat!)</span>
                        </div>
                    @endif
                </div>

                @if($property->is_available)
                    <form action="{{ route('bookings.store', $property->slug) }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Mulai Masuk
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        </div>

                        <!-- Durasi Sewa -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Durasi Sewa
                            </label>
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" @click="duration = 1" :class="duration === 1 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700 hover:border-slate-300'" class="py-2 text-center text-xs border rounded-xl transition-all">
                                    1 Bln
                                </button>
                                <button type="button" @click="duration = 3" :class="duration === 3 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700 hover:border-slate-300'" class="py-2 text-center text-xs border rounded-xl transition-all">
                                    3 Bln
                                </button>
                                <button type="button" @click="duration = 6" :class="duration === 6 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold' : 'border-slate-200 text-slate-700 hover:border-slate-300'" class="py-2 text-center text-xs border rounded-xl transition-all">
                                    6 Bln
                                </button>
                                <button type="button" @click="duration = 12" :class="duration === 12 ? 'border-brand-600 bg-brand-50 text-brand-700 font-bold ring-1 ring-brand-500' : 'border-slate-200 text-slate-700 hover:border-slate-300'" class="py-2 text-center text-xs border rounded-xl transition-all relative">
                                    12 Bln
                                    <span class="absolute -top-2 -right-1 px-1 py-0.2 bg-amber-500 text-white text-[9px] font-bold rounded-full">Hemat</span>
                                </button>
                            </div>
                            <input type="hidden" name="duration_months" :value="duration">
                        </div>

                        <!-- Jumlah Calon Penghuni -->
                        <div>
                            <label for="occupants_count" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jumlah Penghuni
                            </label>
                            <select name="occupants_count" id="occupants_count" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none text-slate-700">
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang (Keluarga)</option>
                                <option value="5">5+ Orang</option>
                            </select>
                        </div>

                        <!-- Data Penyewa (Prefilled if logged in) -->
                        <div class="space-y-3 pt-3 border-t border-slate-100">
                            <p class="text-xs font-bold text-slate-900">Data Pemesan / Penyewa</p>

                            <div>
                                <input type="text" name="tenant_name" value="{{ auth()->user()->name ?? '' }}" placeholder="Nama Lengkap Sesuai KTP" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            </div>

                            <div>
                                <input type="text" name="tenant_phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="Nomor WhatsApp (Aktif)" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            </div>

                            <div>
                                <select name="payment_method" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none text-slate-700">
                                    <option value="transfer_bca">Transfer Bank BCA</option>
                                    <option value="transfer_mandiri">Transfer Bank Mandiri</option>
                                    <option value="qris">QRIS (Semua E-Wallet / Bank)</option>
                                    <option value="tunai">Bayar Tunai di Tempat</option>
                                </select>
                            </div>
                        </div>

                        <!-- Live Price Calculation -->
                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                            <div class="flex justify-between text-slate-600">
                                <span>Biaya Sewa (<span x-text="duration"></span> Bulan)</span>
                                <span class="font-bold text-slate-900" x-text="formatRupiah(totalRent)"></span>
                            </div>

                            <div class="flex justify-between text-slate-600">
                                <span class="flex items-center gap-1">
                                    Deposit Jaminan
                                    <span class="text-[10px] text-slate-400" title="Deposit jaminan akan dikembalikan utuh saat masa sewa berakhir jika tidak ada kerusakan.">(Refundable)</span>
                                </span>
                                <span class="font-bold text-slate-900" x-text="formatRupiah(deposit)"></span>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex justify-between text-sm font-extrabold text-slate-900">
                                <span>Total Pembayaran</span>
                                <span class="text-brand-600 text-base" x-text="formatRupiah(grandTotal)"></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        @auth
                            <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.02]">
                                Ajukan Booking Sekarang
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.02]">
                                Masuk untuk Booking
                            </a>
                        @endauth
                    </form>
                @else
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-center">
                        <p class="text-sm font-bold text-rose-800">Kontrakan Ini Sedang Tersewa</p>
                        <p class="text-xs text-rose-600 mt-1">Anda dapat menghubungi pemilik untuk menanyakan jadwal ketersediaan berikutnya.</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->user->phone ?? '081289123456') }}" target="_blank" class="mt-3 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-rose-600 text-white font-bold text-xs rounded-xl shadow-sm hover:bg-rose-700">
                            Tanya Antrean via WA
                        </a>
                    </div>
                @endif

                <!-- Safety Note -->
                <div class="pt-4 border-t border-slate-100 flex items-center gap-3 text-[11px] text-slate-500">
                    <svg class="w-8 h-8 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Garansi bebas penipuan. Uang sewa aman dengan verifikasi dan bukti pembayaran resmi.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Properties Recommendation -->
    @if($similarProperties->isNotEmpty())
        <div class="mt-20 pt-10 border-t border-slate-200">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Kontrakan Lainnya di Area Sekitar</h2>
                    <p class="text-xs text-slate-500 mt-1">Pilihan alternatif di kota {{ $property->city }} yang mungkin Anda sukai.</p>
                </div>
                <a href="{{ route('properties.index', ['city' => $property->city]) }}" class="text-xs font-bold text-brand-600 hover:underline">
                    Lihat di {{ $property->city }} &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($similarProperties as $sim)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 hover:border-brand-500 shadow-sm hover:shadow-lg transition-all group flex flex-col">
                        <div class="relative h-44 overflow-hidden bg-slate-100">
                            <img src="{{ $sim->main_image }}" alt="{{ $sim->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/90 text-slate-800">
                                    {{ $sim->property_type_label }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <p class="text-xs text-slate-500">{{ $sim->city }}</p>
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1 mt-0.5">
                                    <a href="{{ route('properties.show', $sim->slug) }}">
                                        {{ $sim->title }}
                                    </a>
                                </h4>
                            </div>
                            <div class="mt-4 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-extrabold text-brand-600">{{ $sim->formatted_price_month }} <span class="text-[10px] text-slate-400 font-normal">/bln</span></span>
                                <a href="{{ route('properties.show', $sim->slug) }}" class="text-xs font-bold text-slate-700 hover:text-brand-600">Detail &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
