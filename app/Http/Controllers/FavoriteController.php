<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display the tenant's wishlist / favorites with comparison table.
     */
    public function index(Request $request): View
    {
        $favorites = Favorite::where('user_id', $request->user()->id)
            ->with(['property.user', 'property.reviews'])
            ->latest()
            ->paginate(12);

        $properties = $favorites->getCollection()->pluck('property')->filter();

        return view('favorites.index', compact('favorites', 'properties'));
    }

    /**
     * Toggle a property in user's favorites.
     */
    public function toggle(Request $request, Property $property): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
            $message = 'Properti dihapus dari daftar favorit.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
            $favorited = true;
            $message = 'Properti berhasil disimpan ke daftar favorit!';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message,
                'favorites_count' => Favorite::where('user_id', $user->id)->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
