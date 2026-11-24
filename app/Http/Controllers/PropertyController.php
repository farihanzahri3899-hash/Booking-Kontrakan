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
            ->with(['reviews', 'user'])
            ->filter($filters)
            ->paginate(9)
            ->withQueryString();

        $dbCities = Property::query()
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort()
            ->values();

        $popularCities = collect([
            'Kota Jambi',
            'Mendalo (UNJA)',
            'Telanaipura',
            'Muaro Jambi',
            'Palembang',
            'Padang',
            'Pekanbaru',
            'Bandung',
            'Yogyakarta',
        ]);

        $cities = $dbCities->merge($popularCities)->unique()->values();

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
            ->with(['reviews', 'user'])
            ->take(3)
            ->get();

        if ($similarProperties->isEmpty()) {
            $similarProperties = Property::query()
                ->where('id', '!=', $property->id)
                ->where('property_type', $property->property_type)
                ->available()
                ->with(['reviews', 'user'])
                ->take(3)
                ->get();
        }

        return view('properties.show', compact('property', 'similarProperties'));
    }
}
