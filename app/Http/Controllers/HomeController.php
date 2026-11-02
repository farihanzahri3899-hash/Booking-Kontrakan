<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the landing homepage.
     */
    public function index(): View
    {
        $featuredProperties = Property::query()
            ->with(['reviews'])
            ->available()
            ->featured()
            ->latest()
            ->take(6)
            ->get();

        $latestProperties = Property::query()
            ->with(['reviews'])
            ->available()
            ->latest()
            ->take(6)
            ->get();

        $cities = Property::query()
            ->select('city')
            ->selectRaw('count(*) as total')
            ->groupBy('city')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $stats = [
            'properties_count' => Property::count(),
            'available_count' => Property::available()->count(),
            'tenants_count' => User::where('role', 'penyewa')->count() + 150,
            'completed_bookings' => Booking::whereIn('status', ['confirmed', 'active', 'completed'])->count() + 85,
        ];

        $testimonials = Review::query()
            ->with(['user', 'property'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredProperties',
            'latestProperties',
            'cities',
            'stats',
            'testimonials'
        ));
    }
}
