<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
        ]);

        $bookingId = $validated['booking_id'] ?? null;

        // Check if tenant has booked this property or completed booking
        if ($bookingId) {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->first();

            if (! $booking) {
                return back()->with('error', 'Booking tidak valid.');
            }

            if (Review::where('booking_id', $bookingId)->exists()) {
                return back()->with('error', 'Anda sudah memberikan review untuk booking ini.');
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'booking_id' => $bookingId,
            'rating' => (int) $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan rating yang Anda berikan!');
    }

    /**
     * Owner responds to a review for their property.
     */
    public function reply(Request $request, Review $review): RedirectResponse
    {
        $user = Auth::user();
        $property = $review->property;

        if ($property->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Hanya pemilik properti atau admin yang dapat membalas ulasan ini.');
        }

        $validated = $request->validate([
            'owner_reply' => ['required', 'string', 'min:3', 'max:1500'],
        ], [
            'owner_reply.required' => 'Balasan ulasan tidak boleh kosong.',
            'owner_reply.min' => 'Balasan ulasan minimal 3 karakter.',
        ]);

        $review->update([
            'owner_reply' => trim($validated['owner_reply']),
            'owner_replied_at' => now(),
        ]);

        return back()->with('success', 'Balasan ulasan berhasil dikirim!');
    }
}
