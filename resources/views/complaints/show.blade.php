@extends('layouts.app')

@section('title', 'Detail Laporan: ' . $complaint->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Back button -->
    <a href="{{ route('complaints.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-brand-600 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Laporan
    </a>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <!-- Header Banner with Status Tracker -->
        <div class="p-6 sm:p-8 bg-slate-50/70 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $complaint->status_badge_class }}">
                            {{ $complaint->status_label }}
                        </span>
                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                            {{ $complaint->category_label }}
                        </span>
                        <span class="text-xs text-slate-600">
                            ID Laporan #{{ $complaint->id }} &bull; {{ $complaint->created_at->isoFormat('D MMMM Y, H:mm') }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        {{ $complaint->title }}
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('chat.start', $complaint->property) }}" method="POST">
                        @csrf
                        <input type="hidden" name="initial_message" value="Halo, saya menanyakan tindak lanjut laporan kendala #{{ $complaint->id }}: {{ $complaint->title }}">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Chat Pemilik
                        </button>
                    </form>
                </div>
            </div>

            <!-- Visual Progress Tracker -->
            <div class="mt-8 pt-6 border-t border-slate-200/60">
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ in_array($complaint->status, ['pending', 'in_progress', 'resolved']) ? 'bg-brand-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            1
                        </div>
                        <span class="mt-1 font-bold text-slate-900">Laporan Diajukan</span>
                        <span class="text-[10px] text-slate-600">{{ $complaint->created_at->format('d M H:i') }}</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ in_array($complaint->status, ['in_progress', 'resolved']) ? 'bg-brand-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            2
                        </div>
                        <span class="mt-1 font-bold text-slate-900">Diproses Pemilik</span>
                        <span class="text-[10px] text-slate-600">
                            {{ $complaint->status === 'in_progress' ? 'Sedang Ditangani' : ($complaint->status === 'resolved' ? 'Selesai' : 'Menunggu') }}
                        </span>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $complaint->status === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            3
                        </div>
                        <span class="mt-1 font-bold text-slate-900">Selesai Diperbaiki</span>
                        <span class="text-[10px] text-slate-600">
                            {{ $complaint->resolved_at ? $complaint->resolved_at->format('d M H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <!-- Property & Owner Info Box -->
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <img src="{{ $complaint->property->main_image ?? 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=200&auto=format&fit=crop&q=80' }}" class="w-16 h-16 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] font-bold text-brand-600 uppercase">Unit Kontrakan</p>
                    <h3 class="text-base font-bold text-slate-900 truncate">
                        <a href="{{ route('properties.show', $complaint->property->slug) }}" class="hover:underline">
                            {{ $complaint->property->title }}
                        </a>
                    </h3>
                    <p class="text-xs text-slate-600 truncate mt-0.5">
                        Pemilik: <span class="font-semibold text-slate-800">{{ $complaint->property->user->name ?? 'Pemilik' }}</span> &bull; Telp: {{ $complaint->property->user->phone ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Detail Keluhan -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">
                    Uraian Kendala dari Penyewa
                </h2>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $complaint->description }}
                </div>
            </div>

            <!-- Foto Bukti Kerusakan jika ada -->
            @if($complaint->photo)
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Foto Bukti Kerusakan
                    </h2>
                    <div class="inline-block rounded-2xl overflow-hidden border border-slate-200 shadow-sm max-w-md">
                        <img src="{{ asset('storage/' . $complaint->photo) }}" alt="Bukti kendala" class="w-full h-auto object-cover max-h-96">
                    </div>
                </div>
            @endif

            <!-- Tanggapan Resmi Pemilik -->
            <div class="pt-6 border-t border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">
                    Tanggapan & Solusi dari Pemilik Properti
                </h2>

                @if($complaint->owner_response)
                    <div class="p-6 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-emerald-950">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <div>
                                <p class="text-xs font-bold text-emerald-900">{{ $complaint->property->user->name ?? 'Pemilik Kontrakan' }}</p>
                                <p class="text-[10px] text-emerald-700">Tanggapan Resmi &bull; Diperbarui {{ $complaint->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-sm whitespace-pre-line leading-relaxed mt-3 text-slate-800 pl-9">
                            {{ $complaint->owner_response }}
                        </div>
                    </div>
                @else
                    <div class="p-6 rounded-2xl bg-amber-50/60 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="font-bold text-amber-950 mb-0.5">Menunggu Tanggapan Pemilik</p>
                            <p class="text-xs text-amber-800">
                                Laporan ini sudah diteruskan ke pemilik properti. Pemilik biasanya merespon dan menjadwalkan perbaikan dalam waktu 1x24 jam. Anda juga dapat mengirim pesan langsung via tombol Chat di atas.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
