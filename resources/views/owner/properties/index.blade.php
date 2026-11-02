@extends('layouts.app')

@section('title', 'Kelola Kontrakan Saya')

@section('content')
<div class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('owner.dashboard') }}" class="hover:text-brand-600">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Kelola Kontrakan</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Kontrakan Saya</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola data properti, ubah status ketersediaan, atau perbarui harga sewa.</p>
            </div>

            <a href="{{ route('owner.properties.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Kontrakan Baru</span>
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between gap-4">
        <form action="{{ route('owner.properties.index') }}" method="GET" class="flex-1 flex items-center gap-3">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kontrakan atau kota..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs rounded-xl transition-colors">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('owner.properties.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <span class="text-xs text-slate-500 font-semibold hidden sm:inline">Total: {{ $properties->total() }} Unit</span>
    </div>

    <!-- Properties Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        @if($properties->isEmpty())
            <div class="p-12 text-center text-xs text-slate-400">
                Belum ada kontrakan yang terdaftar. Klik "Tambah Kontrakan Baru" untuk mulai menyewakan properti Anda!
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-6">Kontrakan</th>
                            <th class="py-3.5 px-6">Tipe & Lokasi</th>
                            <th class="py-3.5 px-6">Harga Sewa</th>
                            <th class="py-3.5 px-6">Spesifikasi</th>
                            <th class="py-3.5 px-6">Status Ketersediaan</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($properties as $prop)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Properti & Thumbnail -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $prop->main_image }}" alt="{{ $prop->title }}" class="w-14 h-14 rounded-xl object-cover shrink-0 border border-slate-200">
                                        <div class="min-w-0">
                                            <a href="{{ route('properties.show', $prop->slug) }}" class="font-bold text-slate-900 hover:text-brand-600 text-sm line-clamp-1">
                                                {{ $prop->title }}
                                            </a>
                                            <span class="text-[11px] text-slate-400 block mt-0.5">Slug: {{ $prop->slug }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tipe & Lokasi -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800">{{ $prop->property_type_label }}</span>
                                    <span class="block text-slate-500 text-[11px] mt-0.5">{{ $prop->city }}</span>
                                </td>

                                <!-- Harga Sewa -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-brand-600 text-sm">{{ $prop->formatted_price_month }}</span>
                                    <span class="block text-slate-400 text-[10px]">/ bulan</span>
                                </td>

                                <!-- Spesifikasi -->
                                <td class="py-4 px-6 text-slate-600">
                                    <span>{{ $prop->bedrooms }} KT &bull; {{ $prop->bathrooms }} KM</span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">{{ $prop->building_size ?? '-' }} m²</span>
                                </td>

                                <!-- Status Toggle -->
                                <td class="py-4 px-6">
                                    <form action="{{ route('owner.properties.toggle', $prop->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border transition-colors {{ $prop->is_available ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' }}" title="Klik untuk ubah status">
                                            <span class="w-2 h-2 rounded-full {{ $prop->is_available ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            <span>{{ $prop->is_available ? 'Tersedia' : 'Penuh' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right space-x-1 whitespace-nowrap">
                                    <a href="{{ route('properties.show', $prop->slug) }}" target="_blank" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg inline-block">
                                        Lihat
                                    </a>

                                    <a href="{{ route('owner.properties.edit', $prop->id) }}" class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[11px] rounded-lg inline-block">
                                        Edit
                                    </a>

                                    <form action="{{ route('owner.properties.destroy', $prop->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontrakan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg inline-block">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
