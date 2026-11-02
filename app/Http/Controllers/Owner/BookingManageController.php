<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingManageController extends Controller
{
    /**
     * Display a listing of bookings for the owner's properties.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $q = $request->query('q');

        $propertyIds = Property::where('user_id', Auth::id())->pluck('id');

        $query = Booking::query()
            ->whereIn('property_id', $propertyIds)
            ->with(['property', 'user', 'review'])
            ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('booking_code', 'like', "%{$q}%")
                    ->orWhere('tenant_name', 'like', "%{$q}%")
                    ->orWhere('tenant_phone', 'like', "%{$q}%")
                    ->orWhereHas('property', function ($p) use ($q) {
                        $p->where('title', 'like', "%{$q}%");
                    });
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::whereIn('property_id', $propertyIds)->count(),
            'pending' => Booking::whereIn('property_id', $propertyIds)->where('status', 'pending')->count(),
            'active' => Booking::whereIn('property_id', $propertyIds)->whereIn('status', ['confirmed', 'active'])->count(),
            'completed' => Booking::whereIn('property_id', $propertyIds)->where('status', 'completed')->count(),
        ];

        return view('owner.bookings.index', compact('bookings', 'status', 'q', 'counts'));
    }

    /**
     * Confirm a booking request.
     */
    public function confirm(Booking $booking): RedirectResponse
    {
        if ($booking->property->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        return back()->with('success', "Booking {$booking->booking_code} berhasil disetujui! Status: Dikonfirmasi.");
    }

    /**
     * Verify payment and activate booking.
     */
    public function verifyPayment(Booking $booking): RedirectResponse
    {
        if ($booking->property->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->update([
            'payment_status' => 'verified',
            'status' => 'active',
        ]);

        // Mark property as occupied
        $booking->property->update(['is_available' => false]);

        return back()->with('success', "Pembayaran booking {$booking->booking_code} telah diverifikasi. Kontrakan kini berstatus Tersewa / Dihuni.");
    }

    /**
     * Reject a booking request.
     */
    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->property->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return back()->with('info', "Booking {$booking->booking_code} telah ditolak dengan alasan yang tercatat.");
    }

    /**
     * Complete a rental period.
     */
    public function complete(Booking $booking): RedirectResponse
    {
        if ($booking->property->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->update([
            'status' => 'completed',
        ]);

        // Release property back to available
        $booking->property->update(['is_available' => true]);

        return back()->with('success', "Masa sewa booking {$booking->booking_code} telah diselesaikan. Kontrakan kembali berstatus Tersedia.");
    }
}
