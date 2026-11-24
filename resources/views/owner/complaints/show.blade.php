@extends('layouts.app')

@section('title', 'Tanggapi Laporan Kendala - Panel Pemilik')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Back button -->
    <a href="{{ route('owner.complaints.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-brand-600 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Laporan Penyewa
    </a>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 sm:p-8 bg-slate-50/70 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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

            <!-- Quick Chat Button -->
            <form action="{{ route('chat.start', $complaint->property) }}" method="POST">
                @csrf
                <input type="hidden" name="initial_message" value="Halo {{ $complaint->user->name }}, saya telah membaca laporan kendala #{{ $complaint->id }} Anda mengenai: {{ $complaint->title }}.">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Kirim Chat ke Penyewa
                </button>
            </form>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <!-- Info Penyewa & Properti -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Penyewa yang Melaporkan</p>
                    <p class="text-sm font-bold text-slate-900">{{ $complaint->user->name }}</p>
                    <p class="text-xs text-slate-600">{{ $complaint->user->email }} &bull; {{ $complaint->user->phone ?? 'Tidak ada No. HP' }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Unit Kontrakan</p>
                    <p class="text-sm font-bold text-slate-900 truncate">{{ $complaint->property->title }}</p>
                    <p class="text-xs text-slate-600">{{ $complaint->property->city }} &bull; {{ $complaint->property->address }}</p>
                </div>
            </div>

            <!-- Uraian Keluhan -->
            <div>
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                    Uraian Kendala dari Penyewa
                </h2>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $complaint->description }}
                </div>
            </div>

            <!-- Foto Bukti Kerusakan jika ada -->
            @if($complaint->photo)
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Foto Bukti yang Diunggah Penyewa
                    </h2>
                    <div class="inline-block rounded-2xl overflow-hidden border border-slate-200 shadow-sm max-w-md">
                        <img src="{{ asset('storage/' . $complaint->photo) }}" alt="Bukti kendala" class="w-full h-auto object-cover max-h-96">
                    </div>
                </div>
            @endif

            <!-- Form Tanggapan Pemilik -->
            <div class="pt-6 border-t border-slate-100">
                <h2 class="text-base font-extrabold text-slate-900 tracking-tight mb-2">
                    Formulir Tindak Lanjut & Tanggapan Pemilik
                </h2>
                <p class="text-xs text-slate-600 mb-6">
                    Pilih status penanganan terkini dan berikan instruksi/solusi untuk penyewa (misal: "Teknisi AC akan datang besok pagi jam 10:00").
                </p>

                <form action="{{ route('owner.complaints.respond', $complaint) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                            Ubah Status Penanganan <span class="text-rose-500">*</span>
                        </label>
                        <select id="status" name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900 bg-white" required>
                            <option value="in_progress" {{ old('status', $complaint->status) === 'in_progress' ? 'selected' : '' }}>
                                Sedang Diproses (Teknisi dijadwalkan / dalam perbaikan)
                            </option>
                            <option value="resolved" {{ old('status', $complaint->status) === 'resolved' ? 'selected' : '' }}>
                                Selesai (Fasilitas sudah diperbaiki)
                            </option>
                            <option value="rejected" {{ old('status', $complaint->status) === 'rejected' ? 'selected' : '' }}>
                                Ditolak (Bukan tanggung jawab pemilik / kendala tidak relevan)
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="owner_response" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                            Pesan Tanggapan Resmi ke Penyewa <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="owner_response" name="owner_response" rows="4" placeholder="Tuliskan konfirmasi Anda, jadwal kedatangan tukang/teknisi, atau solusi yang diberikan..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900" required>{{ old('owner_response', $complaint->owner_response) }}</textarea>
                        @error('owner_response')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-700 shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                            Simpan & Kirim Tanggapan ke Penyewa &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
