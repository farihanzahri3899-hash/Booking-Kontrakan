@extends('layouts.app')

@section('title', 'Verifikasi Identitas Digital / KYC (Centang Biru)')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb & Tag -->
    <div class="flex items-center gap-2 mb-2">
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
            <svg class="w-3.5 h-3.5 fill-current text-sky-500" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Keamanan & Kepercayaan
        </span>
    </div>

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
        Verifikasi Identitas Digital (Centang Biru)
    </h1>
    <p class="text-sm text-slate-600 mb-8 max-w-2xl leading-relaxed">
        Dapatkan lencana centang biru <strong>"Verified User"</strong> dengan mengunggah KTP, KTM (Kartu Mahasiswa), atau SIM Anda. Mencegah penipuan properti bodong dan meningkatkan kepercayaan antara pemilik dan penyewa.
    </p>

    <!-- Status Banner -->
    @if($user->isVerified())
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-emerald-500/20 mb-8 flex flex-col sm:flex-row items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0">
                <svg class="w-10 h-10 text-white fill-current" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/30 text-white mb-1">
                    Akun Terverifikasi Resmi
                </span>
                <h2 class="text-xl font-extrabold text-white">Selamat, Identitas Anda Telah Terverifikasi!</h2>
                <p class="text-xs sm:text-sm text-emerald-50 mt-1 leading-relaxed">
                    Lencana centang biru telah aktif pada profil, ulasan, dan listing Anda. Tipe Identitas: <strong>{{ strtoupper($user->kyc_id_type) }}</strong> (No: {{ $user->kyc_id_number }}).
                </p>
                <p class="text-[11px] text-emerald-100 mt-2">Diverifikasi pada: {{ $user->kyc_verified_at ? $user->kyc_verified_at->isoFormat('D MMMM Y') : 'Aktif' }}</p>
            </div>
        </div>
    @elseif($user->isKycPending())
        <div class="bg-amber-50 rounded-3xl border border-amber-200 p-6 sm:p-8 mb-8 flex flex-col sm:flex-row items-start gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-200 text-amber-900 mb-1">
                    Sedang Ditinjau Admin
                </span>
                <h2 class="text-lg font-bold text-amber-950">Dokumen Anda Sedang Dalam Proses Verifikasi</h2>
                <p class="text-xs sm:text-sm text-amber-800 mt-1 leading-relaxed">
                    Tim Administrator kami sedang memeriksa kecocokan data dokumen identitas Anda ({{ strtoupper($user->kyc_id_type) }} - {{ $user->kyc_id_number }}). Proses verifikasi biasanya memakan waktu maksimal 1x24 jam kerja.
                </p>
            </div>
        </div>
    @elseif($user->kyc_status === 'rejected')
        <div class="bg-rose-50 rounded-3xl border border-rose-200 p-6 sm:p-8 mb-8 flex flex-col sm:flex-row items-start gap-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-200 text-rose-900 mb-1">
                    Verifikasi Ditolak
                </span>
                <h2 class="text-lg font-bold text-rose-950">Pengajuan Verifikasi Identitas Belum Sesuai</h2>
                <p class="text-xs sm:text-sm text-rose-800 mt-1 leading-relaxed">
                    Alasan dari Admin: <strong>"{{ $user->kyc_rejection_reason ?? 'Foto dokumen buram atau tidak terbaca.' }}"</strong>. Silakan periksa kembali dan unggah ulang dokumen yang jelas melalui formulir di bawah ini.
                </p>
            </div>
        </div>
    @endif

    <!-- Form Pengajuan KYC jika belum verified -->
    @if(!$user->isVerified() && !$user->isKycPending())
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Unggah Dokumen Identitas</h2>
            <p class="text-xs text-slate-600 mb-6">Pastikan foto kartu identitas tidak terpotong, tidak ada pantulan cahaya, dan teks dapat terbaca jelas.</p>

            <form action="{{ route('kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="kyc_id_type" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                            Pilih Jenis Kartu Identitas <span class="text-rose-500">*</span>
                        </label>
                        <select id="kyc_id_type" name="kyc_id_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900 bg-white" required>
                            <option value="ktp" {{ old('kyc_id_type') == 'ktp' ? 'selected' : '' }}>KTP (Kartu Tanda Penduduk)</option>
                            <option value="ktm" {{ old('kyc_id_type') == 'ktm' ? 'selected' : '' }}>KTM (Kartu Tanda Mahasiswa)</option>
                            <option value="sim" {{ old('kyc_id_type') == 'sim' ? 'selected' : '' }}>SIM (Surat Izin Mengemudi)</option>
                            <option value="paspor" {{ old('kyc_id_type') == 'paspor' ? 'selected' : '' }}>Paspor</option>
                        </select>
                        @error('kyc_id_type')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kyc_id_number" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                            Nomor Identitas (NIK / NIM) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="kyc_id_number" name="kyc_id_number" value="{{ old('kyc_id_number', $user->kyc_id_number) }}" placeholder="Contoh: 1571012345670001 atau F1E121045" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-brand-500 text-sm font-medium text-slate-900" required>
                        @error('kyc_id_number')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Foto Dokumen -->
                <div>
                    <label for="kyc_document" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Foto Kartu Identitas (Asli) <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" id="kyc_document" name="kyc_document" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" required>
                    <p class="text-[11px] text-slate-600 mt-1">Format JPG, JPEG, PNG maksimal 3MB.</p>
                    @error('kyc_document')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Selfie / Pegang Kartu -->
                <div>
                    <label for="kyc_selfie" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">
                        Foto Selfie Sambil Memegang Kartu Identitas (Opsional untuk proses lebih cepat)
                    </label>
                    <input type="file" id="kyc_selfie" name="kyc_selfie" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <p class="text-[11px] text-slate-600 mt-1">Format JPG, JPEG, PNG maksimal 3MB.</p>
                    @error('kyc_selfie')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-[11px] text-slate-600 max-w-sm">
                        Data identitas Anda disimpan secara aman dan terenkripsi, hanya digunakan untuk validasi profil anti-fraud.
                    </p>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-700 shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                        Kirim untuk Diverifikasi &rarr;
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Keuntungan Centang Biru -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold mb-3">
                🛡️
            </div>
            <h3 class="text-sm font-bold text-slate-900 mb-1">Bebas Risiko Penipuan</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Penyewa dan pemilik yang terverifikasi memberikan jaminan bahwa properti dan individu yang bertransaksi adalah nyata.
            </p>
        </div>
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold mb-3">
                ⚡
            </div>
            <h3 class="text-sm font-bold text-slate-900 mb-1">Booking Lebih Cepat Disetujui</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Pemilik properti memprioritaskan calon penyewa dengan centang biru karena identitasnya terjamin.
            </p>
        </div>
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold mb-3">
                ⭐
            </div>
            <h3 class="text-sm font-bold text-slate-900 mb-1">Lencana Khusus di Profil</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Centang biru tampil di samping nama Anda di obrolan chat, ulasan properti, dan detail pemilik.
            </p>
        </div>
    </div>
</div>
@endsection
