@extends('layouts.app')

@section('title', 'Verifikasi KYC Identitas Pengguna - Panel Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ rejectModalOpen: false, rejectUserId: '', rejectUserName: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <svg class="w-3.5 h-3.5 fill-current text-indigo-600" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Pusat Verifikasi KYC
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Validasi Identitas Digital (Centang Biru)
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Periksa kesesuaian KTP / KTM pengguna dan berikan centang biru resmi untuk menjamin keamanan transaksi.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                &larr; Dashboard Admin
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6 pb-2 border-b border-slate-200">
        <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Verifikasi ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.kyc.index', ['status' => 'verified']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $status === 'verified' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Terverifikasi ({{ $counts['verified'] }})
        </a>
        <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Ditolak ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.kyc.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ $status === 'all' ? 'bg-slate-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua Riwayat ({{ $counts['all'] }})
        </a>
    </div>

    @if($users->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-md mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Tidak Ada Pengajuan KYC</h3>
            <p class="text-xs text-slate-600">Saat ini tidak ada dokumen identitas pengguna pada status ini.</p>
        </div>
    @else
        <!-- Submissions Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-slate-100">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-600 uppercase">
                        <tr>
                            <th class="py-4 px-5">Pengguna & Peran</th>
                            <th class="py-4 px-5">Dokumen Identitas</th>
                            <th class="py-4 px-5">Foto Kartu & Selfie</th>
                            <th class="py-4 px-5">Status</th>
                            <th class="py-4 px-5 text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $u->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=059669&color=fff' }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs">{{ $u->name }}</p>
                                            <p class="text-[11px] text-slate-600">{{ $u->email }}</p>
                                            <span class="inline-block mt-0.5 px-2 py-0.2 rounded text-[10px] font-bold uppercase {{ $u->role === 'pemilik' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $u->role }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <span class="font-bold text-xs uppercase px-2 py-0.5 rounded bg-sky-50 text-sky-800 border border-sky-200">
                                        {{ $u->kyc_id_type }}
                                    </span>
                                    <p class="text-xs font-mono font-bold text-slate-900 mt-1">
                                        {{ $u->kyc_id_number }}
                                    </p>
                                </td>

                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2">
                                        @if($u->kyc_document_path)
                                            <a href="{{ asset('storage/' . $u->kyc_document_path) }}" target="_blank" class="group relative rounded-lg overflow-hidden border border-slate-200 inline-block w-14 h-10 bg-slate-100" title="Buka foto dokumen kartu identitas">
                                                <img src="{{ asset('storage/' . $u->kyc_document_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-600">-</span>
                                        @endif

                                        @if($u->kyc_selfie_path)
                                            <a href="{{ asset('storage/' . $u->kyc_selfie_path) }}" target="_blank" class="group relative rounded-lg overflow-hidden border border-slate-200 inline-block w-10 h-10 bg-slate-100" title="Buka foto selfie">
                                                <img src="{{ asset('storage/' . $u->kyc_selfie_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $u->kyc_status_badge_class }}">
                                        {{ $u->kyc_status_label }}
                                    </span>
                                    @if($u->kyc_rejection_reason)
                                        <p class="text-[10px] text-rose-600 mt-1 max-w-xs leading-tight">
                                            Alasan: {{ $u->kyc_rejection_reason }}
                                        </p>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($u->kyc_status !== 'verified')
                                            <form action="{{ route('admin.kyc.approve', $u) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Setujui identitas {{ $u->name }} dan berikan centang biru?')" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    Setujui
                                                </button>
                                            </form>
                                        @endif

                                        @if($u->kyc_status !== 'rejected')
                                            <button type="button" @click="rejectModalOpen = true; rejectUserId = '{{ $u->id }}'; rejectUserName = '{{ $u->name }}'" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-bold text-xs transition-colors">
                                                Tolak
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    @endif

    <!-- Modal Penolakan KYC -->
    <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="rejectModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="rejectModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="rejectModalOpen" x-transition class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8">
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">
                    Tolak Verifikasi Identitas
                </h3>
                <p class="text-xs text-slate-600 mb-4">
                    Berikan alasan penolakan untuk <span class="font-bold text-slate-800" x-text="rejectUserName"></span> agar pengguna dapat memperbaiki fotonya:
                </p>

                <form :action="'/admin/kyc/' + rejectUserId + '/reject'" method="POST">
                    @csrf
                    <textarea name="rejection_reason" rows="3" placeholder="Contoh: Foto kartu identitas buram / nama di kartu berbeda dengan nama akun." class="w-full px-4 py-3 text-xs sm:text-sm border border-slate-200 rounded-2xl focus:border-rose-500 focus:ring-rose-500 text-slate-900" required></textarea>

                    <div class="mt-5 flex items-center justify-end gap-2">
                        <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 shadow-sm">
                            Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
