<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Favorite;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the landing homepage tailored for tenants & property seekers.
     */
    public function index(): View
    {
        $featuredProperties = Property::query()
            ->with(['reviews', 'user'])
            ->available()
            ->featured()
            ->latest()
            ->take(6)
            ->get();

        $latestProperties = Property::query()
            ->with(['reviews', 'user'])
            ->available()
            ->latest()
            ->take(6)
            ->get();

        // Database cities
        $dbCities = Property::query()
            ->select('city')
            ->selectRaw('count(*) as total')
            ->groupBy('city')
            ->orderByDesc('total')
            ->get();

        // Extended list of popular cities for selection
        $allCities = collect([
            'Kota Jambi',
            'Mendalo (UNJA)',
            'Telanaipura',
            'Muaro Jambi',
            'Palembang',
            'Padang',
            'Pekanbaru',
            'Bandung',
            'Yogyakarta',
            'Jakarta Selatan',
            'Depok',
            'Semarang',
            'Surabaya',
            'Malang',
        ])->merge($dbCities->pluck('city'))->unique()->values();

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

        // Personalized data if tenant logged in
        $tenantOverview = null;
        if (Auth::check() && Auth::user()->isTenant()) {
            $user = Auth::user();
            $tenantOverview = [
                'user' => $user,
                'active_bookings' => Booking::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->with('property')
                    ->latest()
                    ->take(2)
                    ->get(),
                'wishlist_count' => Favorite::where('user_id', $user->id)->count(),
                'complaints_count' => Complaint::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->count(),
            ];
        }

        return view('home', compact(
            'featuredProperties',
            'latestProperties',
            'dbCities',
            'allCities',
            'stats',
            'testimonials',
            'tenantOverview'
        ));
    }
}
