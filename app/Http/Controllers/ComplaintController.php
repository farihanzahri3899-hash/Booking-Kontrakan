<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Display tenant's submitted complaints.
     */
    public function index(Request $request): View
    {
        $complaints = Complaint::where('user_id', $request->user()->id)
            ->with(['property.user', 'booking'])
            ->latest()
            ->paginate(10);

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show form to create a new complaint.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        // Get bookings of this tenant that are confirmed or active
        $bookings = Booking::where('user_id', $user->id)
            ->with('property')
            ->latest()
            ->get();

        // Selected booking if any
        $selectedBookingId = $request->query('booking');
        $selectedPropertyId = $request->query('property');

        return view('complaints.create', compact('bookings', 'selectedBookingId', 'selectedPropertyId'));
    }

    /**
     * Store a new complaint.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'title' => 'required|string|max:200',
            'category' => 'required|in:fasilitas,listrik_air,kebersihan,keamanan,lainnya',
            'description' => 'required|string|min:10|max:2000',
            'photo' => 'nullable|image|max:3072', // max 3MB
        ], [
            'property_id.required' => 'Silakan pilih kontrakan yang dilaporkan.',
            'title.required' => 'Judul laporan kendala wajib diisi.',
            'description.required' => 'Deskripsi kendala wajib dijelaskan.',
            'description.min' => 'Deskripsi minimal 10 karakter agar pemilik mudah memahami kendala.',
            'photo.image' => 'Bukti foto harus berupa file gambar.',
            'photo.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'user_id' => $request->user()->id,
            'property_id' => $validated['property_id'],
            'booking_id' => $validated['booking_id'] ?? null,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        // Kirim notifikasi laporan kendala ke pemilik kontrakan
        $complaint->load(['property.user', 'user']);
        $owner = $complaint->property?->user;
        if ($owner) {
            $owner->notify(new \App\Notifications\NewComplaintNotification($complaint));
        }

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Laporan kendala berhasil dikirim ke pemilik kontrakan!');
    }

    /**
     * Display complaint detail for tenant.
     */
    public function show(Request $request, Complaint $complaint): View
    {
        // Tenant can only see their own complaints
        if ($complaint->user_id !== $request->user()->id && ! $request->user()->isOwnerOrAdmin()) {
            abort(403, 'Akses terbatas untuk pemilik laporan.');
        }

        $complaint->load(['property.user', 'booking', 'user']);

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Owner: List incoming complaints for their properties.
     */
    public function ownerIndex(Request $request): View
    {
        $owner = $request->user();
        $propertyIds = $owner->properties()->pluck('id');

        $statusFilter = $request->query('status');

        $complaints = Complaint::whereIn('property_id', $propertyIds)
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->with(['property', 'user', 'booking'])
            ->latest()
            ->paginate(12);

        $counts = [
            'all' => Complaint::whereIn('property_id', $propertyIds)->count(),
            'pending' => Complaint::whereIn('property_id', $propertyIds)->where('status', 'pending')->count(),
            'in_progress' => Complaint::whereIn('property_id', $propertyIds)->where('status', 'in_progress')->count(),
            'resolved' => Complaint::whereIn('property_id', $propertyIds)->where('status', 'resolved')->count(),
        ];

        return view('owner.complaints.index', compact('complaints', 'counts', 'statusFilter'));
    }

    /**
     * Owner: Show and respond to a complaint.
     */
    public function ownerShow(Request $request, Complaint $complaint): View
    {
        $owner = $request->user();
        $property = $complaint->property;

        if ($property->user_id !== $owner->id && ! $owner->isAdmin()) {
            abort(403, 'Anda bukan pemilik properti ini.');
        }

        $complaint->load(['user', 'property', 'booking']);

        return view('owner.complaints.show', compact('complaint'));
    }

    /**
     * Owner: Submit response and update status of a complaint.
     */
    public function ownerRespond(Request $request, Complaint $complaint): RedirectResponse
    {
        $owner = $request->user();
        $property = $complaint->property;

        if ($property->user_id !== $owner->id && ! $owner->isAdmin()) {
            abort(403, 'Anda bukan pemilik properti ini.');
        }

        $validated = $request->validate([
            'status' => 'required|in:in_progress,resolved,rejected',
            'owner_response' => 'required|string|min:5|max:2000',
        ], [
            'status.required' => 'Silakan pilih status tindak lanjut.',
            'owner_response.required' => 'Tanggapan wajib diisi untuk memberikan kejelasan pada penyewa.',
        ]);

        $complaint->update([
            'status' => $validated['status'],
            'owner_response' => $validated['owner_response'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : $complaint->resolved_at,
        ]);

        return back()->with('success', 'Tanggapan laporan berhasil diperbarui!');
    }
}
