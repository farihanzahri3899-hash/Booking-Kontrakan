<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KycController extends Controller
{
    /**
     * Show KYC verification page and submission form.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('kyc.index', compact('user'));
    }

    /**
     * Submit KYC verification documents.
     */
    public function submit(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isVerified()) {
            return back()->with('info', 'Akun Anda sudah terverifikasi resmi (centang biru).');
        }

        $validated = $request->validate([
            'kyc_id_type' => 'required|in:ktp,ktm,sim,paspor',
            'kyc_id_number' => 'required|string|min:6|max:30',
            'kyc_document' => 'required|image|max:3072', // max 3MB
            'kyc_selfie' => 'nullable|image|max:3072',
        ], [
            'kyc_id_type.required' => 'Silakan pilih jenis dokumen identitas.',
            'kyc_id_number.required' => 'Nomor identitas (NIK / NIM) wajib diisi.',
            'kyc_document.required' => 'Foto dokumen identitas wajib diunggah.',
            'kyc_document.image' => 'File dokumen harus berupa gambar (JPG, PNG).',
            'kyc_document.max' => 'Ukuran foto dokumen maksimal 3MB.',
            'kyc_selfie.max' => 'Ukuran foto selfie maksimal 3MB.',
        ]);

        $docPath = $request->file('kyc_document')->store('kyc_documents', 'public');
        $selfiePath = null;
        if ($request->hasFile('kyc_selfie')) {
            $selfiePath = $request->file('kyc_selfie')->store('kyc_selfies', 'public');
        }

        $user->update([
            'kyc_status' => 'pending',
            'kyc_id_type' => $validated['kyc_id_type'],
            'kyc_id_number' => $validated['kyc_id_number'],
            'kyc_document_path' => $docPath,
            'kyc_selfie_path' => $selfiePath,
            'kyc_rejection_reason' => null,
        ]);

        return redirect()->route('kyc.index')
            ->with('success', 'Dokumen identitas Anda berhasil dikirim! Administrator akan memverifikasi dalam waktu 1x24 jam.');
    }
}
