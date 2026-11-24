@extends('layouts.app')

@section('title', 'Laporan Kendala Sewa Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Pusat Bantuan & Komplain
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Laporan Kendala Sewa
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Laporkan kerusakan fasilitas (AC rusak, kran bocor, lampu mati, dll.) langsung ke pemilik kontrakan.
            </p>
        </div>

        <a href="{{ route('complaints.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-sm hover:bg-brand-700 shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02] self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan Baru
        </a>
    </div>

    @if($complaints->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-16 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Laporan Kendala</h3>
            <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                Kondisi kontrakan aman dan nyaman! Jika sewaktu-waktu ada fasilitas yang rusak atau butuh perbaikan, ajukan laporan di sini.
            </p>
            <a href="{{ route('complaints.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-700 shadow-sm transition-colors">
                Laporkan Kendala
            </a>
        </div>
    @else
        <!-- List of Complaints -->
        <div class="space-y-4">
            @foreach($complaints as $complaint)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            @if($complaint->photo)
                                <img src="{{ asset('storage/' . $complaint->photo) }}" alt="Bukti kendala" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover border border-slate-100 flex-shrink-0">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                            @endif

                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $complaint->status_badge_class }}">
                                        {{ $complaint->status_label }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ $complaint->category_label }}
                                    </span>
                                    <span class="text-xs text-slate-600">
                                        {{ $complaint->created_at->isoFormat('D MMMM Y, H:mm') }}
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 mb-1">
                                    <a href="{{ route('complaints.show', $complaint) }}" class="hover:text-brand-600 transition-colors">
                                        {{ $complaint->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-600 font-medium mb-2">
                                    Properti: <a href="{{ route('properties.show', $complaint->property->slug) }}" class="text-brand-700 font-bold hover:underline">{{ $complaint->property->title }}</a>
                                    &bull; Pemilik: {{ $complaint->property->user->name ?? 'Pemilik' }}
                                </p>

                                <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                                    {{ $complaint->description }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:text-right flex sm:flex-col items-center sm:items-end justify-between gap-2 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                            <a href="{{ route('complaints.show', $complaint) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                                Lihat Tanggapan & Detail &rarr;
                            </a>

                            @if($complaint->owner_response)
                                <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Sudah Ditanggapi Pemilik
                                </span>
                            @else
                                <span class="text-[11px] text-amber-700 font-medium">
                                    Menunggu respon pemilik
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $complaints->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
