<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Notifications\NewBookingNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of tenant's bookings.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Booking::query()
            ->where('user_id', Auth::id())
            ->with(['property', 'review'])
            ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(8)->withQueryString();

        $counts = [
            'all' => Booking::where('user_id', Auth::id())->count(),
            'pending' => Booking::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'active' => Booking::where('user_id', Auth::id())->whereIn('status', ['confirmed', 'active'])->count(),
            'completed' => Booking::where('user_id', Auth::id())->where('status', 'completed')->count(),
        ];

        return view('bookings.index', compact('bookings', 'status', 'counts'));
    }

    /**
     * Show booking form for a property.
     */
    public function create(Request $request, Property $property): View|RedirectResponse
    {
        if (! $property->is_available) {
            return redirect()->route('properties.show', $property->slug)
                ->with('error', 'Maaf, kontrakan ini saat ini tidak sedang tersedia untuk disewa.');
        }

        $user = Auth::user();

        return view('bookings.create', compact('property', 'user'));
    }

    /**
     * Store newly created booking in storage.
     */
    public function store(Request $request, Property $property): RedirectResponse
    {
        if (! $property->is_available) {
            return back()->with('error', 'Maaf, kontrakan ini sudah tidak tersedia.');
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:24'],
            'occupants_count' => ['required', 'integer', 'min:1', 'max:10'],
            'tenant_name' => ['required', 'string', 'max:255'],
            'tenant_phone' => ['required', 'string', 'max:25'],
            'tenant_id_card' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'string', 'in:transfer_bca,transfer_mandiri,qris,tunai'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $duration = (int) $validated['duration_months'];
        $endDate = (clone $startDate)->addMonths($duration);

        // Calculate pricing
        $monthlyPrice = $property->price_per_month;

        // Give 5% discount if renting 12 months or more
        $rentTotal = $monthlyPrice * $duration;
        if ($duration >= 12 && $property->price_per_year) {
            $rentTotal = (int) ($property->price_per_year * ($duration / 12));
        }

        $depositPrice = $property->deposit_amount;
        $grandTotal = $rentTotal + $depositPrice;

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'start_date' => $startDate->toDateString(),
            'duration_months' => $duration,
            'end_date' => $endDate->toDateString(),
            'occupants_count' => (int) $validated['occupants_count'],
            'monthly_price' => $monthlyPrice,
            'total_rent_price' => $rentTotal,
            'deposit_price' => $depositPrice,
            'grand_total' => $grandTotal,
            'status' => 'pending',
            'tenant_name' => $validated['tenant_name'],
            'tenant_phone' => $validated['tenant_phone'],
            'tenant_id_card' => $validated['tenant_id_card'] ?? null,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Kirim notifikasi pesanan baru ke pemilik kontrakan
        $booking->load('property.user');
        $owner = $property->user;
        if ($owner) {
            $owner->notify(new NewBookingNotification($booking));
        }

        return redirect()->route('bookings.show', $booking->booking_code)
            ->with('success', 'Permohonan booking berhasil dibuat! Silakan selesaikan pembayaran untuk verifikasi.');
    }

    /**
     * Display booking detail / invoice & payment page.
     */
    public function show(string $bookingCode): View
    {
        $booking = Booking::query()
            ->where('booking_code', $bookingCode)
            ->with(['property.user', 'review', 'user'])
            ->firstOrFail();

        // Ensure user can only view their own booking or owner
        if (Auth::id() !== $booking->user_id && ! Auth::user()->isOwnerOrAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Upload payment proof for booking.
     */
    public function uploadPaymentProof(Request $request, Booking $booking): RedirectResponse
    {
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $proofUrl = 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80';

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $proofUrl = Storage::url($path);
        }

        $booking->update([
            'payment_proof' => $proofUrl,
            'payment_status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Pemilik kontrakan akan memverifikasi pesanan Anda.');
    }

    /**
     * Cancel a booking by tenant.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        if (! in_array($booking->status, ['pending'], true)) {
            return back()->with('error', 'Hanya booking dengan status menunggu konfirmasi yang dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return back()->with('info', 'Booking kontrakan Anda telah dibatalkan.');
    }
}
