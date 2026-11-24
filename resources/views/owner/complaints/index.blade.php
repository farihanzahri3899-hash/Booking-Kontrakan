@extends('layouts.app')

@section('title', 'Kelola Laporan Kendala Penyewa - Panel Pemilik')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Panel Pemilik
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Laporan Kendala dari Penyewa
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Pantau dan berikan tanggapan cepat terhadap keluhan fasilitas atau kerusakan dari penyewa kontrakan Anda.
            </p>
        </div>

        <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors self-start sm:self-auto">
            &larr; Dashboard Pemilik
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6 pb-2 border-b border-slate-200">
        <a href="{{ route('owner.complaints.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ empty($statusFilter) ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('owner.complaints.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Respon ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('owner.complaints.index', ['status' => 'in_progress']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $statusFilter === 'in_progress' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Sedang Diproses ({{ $counts['in_progress'] }})
        </a>
        <a href="{{ route('owner.complaints.index', ['status' => 'resolved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $statusFilter === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Selesai ({{ $counts['resolved'] }})
        </a>
    </div>

    @if($complaints->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-16 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Tidak Ada Laporan Kendala</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Semua fasilitas properti Anda berfungsi dengan baik dan penyewa merasa nyaman!
            </p>
        </div>
    @else
        <!-- Complaints Table / List -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-slate-100">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-600 uppercase">
                        <tr>
                            <th class="py-4 px-5">Penyewa & Unit Kontrakan</th>
                            <th class="py-4 px-5">Kendala yang Dilaporkan</th>
                            <th class="py-4 px-5">Kategori</th>
                            <th class="py-4 px-5">Status</th>
                            <th class="py-4 px-5">Waktu</th>
                            <th class="py-4 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($complaints as $c)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0 font-bold text-slate-700 text-xs">
                                            {{ substr($c->user->name ?? 'P', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 text-xs truncate">{{ $c->user->name ?? 'Penyewa' }}</p>
                                            <p class="text-[11px] text-slate-600 truncate">{{ $c->property->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5 max-w-xs">
                                    <p class="font-bold text-slate-900 text-xs truncate">{{ $c->title }}</p>
                                    <p class="text-[11px] text-slate-600 line-clamp-1 mt-0.5">{{ $c->description }}</p>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $c->category_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $c->status_badge_class }}">
                                        {{ $c->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-xs text-slate-600 whitespace-nowrap">
                                    {{ $c->created_at->diffForHumans() }}
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <a href="{{ route('owner.complaints.show', $c) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-brand-50 text-brand-700 hover:bg-brand-100 border border-brand-200 transition-colors">
                                        Tanggapi &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $complaints->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
