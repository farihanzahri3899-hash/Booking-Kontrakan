<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display owner dashboard.
     */
    public function index(): View
    {
        $ownerId = Auth::id();

        $properties = Property::query()
            ->where('user_id', $ownerId)
            ->get();

        $propertyIds = $properties->pluck('id');

        $stats = [
            'total_properties' => $properties->count(),
            'available_properties' => $properties->where('is_available', true)->count(),
            'occupied_properties' => $properties->where('is_available', false)->count(),
            'pending_bookings' => Booking::whereIn('property_id', $propertyIds)->where('status', 'pending')->count(),
            'active_bookings' => Booking::whereIn('property_id', $propertyIds)->where('status', 'active')->count(),
            'monthly_revenue' => Booking::whereIn('property_id', $propertyIds)
                ->whereIn('status', ['confirmed', 'active'])
                ->sum('monthly_price'),
        ];

        $pendingBookings = Booking::query()
            ->whereIn('property_id', $propertyIds)
            ->where('status', 'pending')
            ->with(['property', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = Booking::query()
            ->whereIn('property_id', $propertyIds)
            ->with(['property', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $recentReviews = Review::query()
            ->whereIn('property_id', $propertyIds)
            ->with(['property', 'user'])
            ->latest()
            ->take(4)
            ->get();

        return view('owner.dashboard', compact('stats', 'pendingBookings', 'recentBookings', 'recentReviews'));
    }
}
