<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties with filtering and search.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'city', 'type', 'min_price', 'max_price', 'bedrooms', 'sort']);

        $properties = Property::query()
            ->with(['reviews'])
            ->filter($filters)
            ->paginate(9)
            ->withQueryString();

        $cities = Property::query()
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        $types = [
            'rumah' => 'Rumah Kontrakan',
            'petakan' => 'Kontrakan Petakan',
            'paviliun' => 'Paviliun Mandiri',
            'kost' => 'Kost Eksklusif',
        ];

        return view('properties.index', compact('properties', 'cities', 'types', 'filters'));
    }

    /**
     * Display property details.
     */
    public function show(string $slug): View
    {
        $property = Property::query()
            ->where('slug', $slug)
            ->with(['user', 'reviews.user'])
            ->firstOrFail();

        $similarProperties = Property::query()
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->available()
            ->with(['reviews'])
            ->take(3)
            ->get();

        if ($similarProperties->isEmpty()) {
            $similarProperties = Property::query()
                ->where('id', '!=', $property->id)
                ->available()
                ->with(['reviews'])
                ->take(3)
                ->get();
        }

        return view('properties.show', compact('property', 'similarProperties'));
    }
}
