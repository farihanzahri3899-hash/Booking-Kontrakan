<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyManageController extends Controller
{
    /**
     * Display a listing of owner properties.
     */
    public function index(Request $request): View
    {
        $q = $request->query('q');

        $query = Property::query()
            ->where('user_id', Auth::id())
            ->with(['reviews', 'bookings']);

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        $properties = $query->latest()->paginate(10)->withQueryString();

        return view('owner.properties.index', compact('properties', 'q'));
    }

    /**
     * Show form for creating a new property.
     */
    public function create(): View
    {
        $availableFacilities = [
            'AC Dingin', 'WiFi Cepat', 'Parkir Mobil', 'Parkir Motor', 'Dapur Pribadi',
            'Kamar Mandi Dalam', 'Kasur Springbed', 'Lemari Pakaian', 'Kulkas', 'Water Heater',
            'Balkon / Teras', 'Mesin Cuci', 'CCTV 24 Jam', 'Ruang Tamu', 'Dapur Bersama',
        ];

        return view('owner.properties.create', compact('availableFacilities'));
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'property_type' => ['required', 'string', 'in:rumah,petakan,paviliun,kost'],
            'description' => ['required', 'string'],
            'price_per_month' => ['required', 'integer', 'min:100000'],
            'price_per_year' => ['nullable', 'integer', 'min:100000'],
            'deposit_amount' => ['nullable', 'integer', 'min:0'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'map_url' => ['nullable', 'string', 'max:500'],
            'bedrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'bathrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'building_size' => ['nullable', 'integer', 'min:10', 'max:1000'],
            'land_size' => ['nullable', 'integer', 'min:10', 'max:2000'],
            'electricity_type' => ['required', 'string', 'in:token,pascabayar,gratis'],
            'water_source' => ['required', 'string', 'in:sumur_bor,pam,gratis'],
            'facilities' => ['nullable', 'array'],
            'rules' => ['nullable', 'string'],
            'main_image_url' => ['nullable', 'url'],
            'main_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $mainImage = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1000&auto=format&fit=crop&q=80';

        if ($request->hasFile('main_image_file')) {
            $path = $request->file('main_image_file')->store('properties', 'public');
            $mainImage = Storage::url($path);
        } elseif (! empty($validated['main_image_url'])) {
            $mainImage = $validated['main_image_url'];
        }

        // Parse rules into array
        $rulesArray = [];
        if (! empty($validated['rules'])) {
            $rulesArray = array_values(array_filter(array_map('trim', explode("\n", $validated['rules']))));
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Property::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        Property::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'property_type' => $validated['property_type'],
            'description' => $validated['description'],
            'price_per_month' => (int) $validated['price_per_month'],
            'price_per_year' => $validated['price_per_year'] ? (int) $validated['price_per_year'] : null,
            'deposit_amount' => (int) ($validated['deposit_amount'] ?? 0),
            'address' => $validated['address'],
            'city' => $validated['city'],
            'district' => $validated['district'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'map_url' => $validated['map_url'] ?? null,
            'bedrooms' => (int) $validated['bedrooms'],
            'bathrooms' => (int) $validated['bathrooms'],
            'building_size' => $validated['building_size'] ? (int) $validated['building_size'] : null,
            'land_size' => $validated['land_size'] ? (int) $validated['land_size'] : null,
            'electricity_type' => $validated['electricity_type'],
            'water_source' => $validated['water_source'],
            'facilities' => $validated['facilities'] ?? [],
            'rules' => $rulesArray,
            'main_image' => $mainImage,
            'images' => [$mainImage],
            'is_available' => true,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('owner.properties.index')
            ->with('success', 'Kontrakan berhasil ditambahkan dan siap disewakan!');
    }

    /**
     * Show form for editing the property.
     */
    public function edit(Property $property): View
    {
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $availableFacilities = [
            'AC Dingin', 'WiFi Cepat', 'Parkir Mobil', 'Parkir Motor', 'Dapur Pribadi',
            'Kamar Mandi Dalam', 'Kasur Springbed', 'Lemari Pakaian', 'Kulkas', 'Water Heater',
            'Balkon / Teras', 'Mesin Cuci', 'CCTV 24 Jam', 'Ruang Tamu', 'Dapur Bersama',
        ];

        return view('owner.properties.edit', compact('property', 'availableFacilities'));
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property): RedirectResponse
    {
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'property_type' => ['required', 'string', 'in:rumah,petakan,paviliun,kost'],
            'description' => ['required', 'string'],
            'price_per_month' => ['required', 'integer', 'min:100000'],
            'price_per_year' => ['nullable', 'integer', 'min:100000'],
            'deposit_amount' => ['nullable', 'integer', 'min:0'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'map_url' => ['nullable', 'string', 'max:500'],
            'bedrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'bathrooms' => ['required', 'integer', 'min:1', 'max:20'],
            'building_size' => ['nullable', 'integer', 'min:10', 'max:1000'],
            'land_size' => ['nullable', 'integer', 'min:10', 'max:2000'],
            'electricity_type' => ['required', 'string', 'in:token,pascabayar,gratis'],
            'water_source' => ['required', 'string', 'in:sumur_bor,pam,gratis'],
            'facilities' => ['nullable', 'array'],
            'rules' => ['nullable', 'string'],
            'main_image_url' => ['nullable', 'url'],
            'main_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $mainImage = $property->main_image;

        if ($request->hasFile('main_image_file')) {
            $path = $request->file('main_image_file')->store('properties', 'public');
            $mainImage = Storage::url($path);
        } elseif (! empty($validated['main_image_url'])) {
            $mainImage = $validated['main_image_url'];
        }

        $rulesArray = [];
        if (! empty($validated['rules'])) {
            $rulesArray = array_values(array_filter(array_map('trim', explode("\n", $validated['rules']))));
        }

        $property->update([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'],
            'description' => $validated['description'],
            'price_per_month' => (int) $validated['price_per_month'],
            'price_per_year' => $validated['price_per_year'] ? (int) $validated['price_per_year'] : null,
            'deposit_amount' => (int) ($validated['deposit_amount'] ?? 0),
            'address' => $validated['address'],
            'city' => $validated['city'],
            'district' => $validated['district'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'map_url' => $validated['map_url'] ?? null,
            'bedrooms' => (int) $validated['bedrooms'],
            'bathrooms' => (int) $validated['bathrooms'],
            'building_size' => $validated['building_size'] ? (int) $validated['building_size'] : null,
            'land_size' => $validated['land_size'] ? (int) $validated['land_size'] : null,
            'electricity_type' => $validated['electricity_type'],
            'water_source' => $validated['water_source'],
            'facilities' => $validated['facilities'] ?? [],
            'rules' => $rulesArray,
            'main_image' => $mainImage,
            'is_available' => $request->has('is_available'),
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('owner.properties.index')
            ->with('success', 'Data kontrakan berhasil diperbarui!');
    }

    /**
     * Toggle availability status of a property.
     */
    public function toggleAvailability(Property $property): RedirectResponse
    {
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $property->update([
            'is_available' => ! $property->is_available,
        ]);

        $statusText = $property->is_available ? 'Tersedia' : 'Penuh / Tersewa';

        return back()->with('success', "Status kontrakan \"{$property->title}\" diubah menjadi {$statusText}.");
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property): RedirectResponse
    {
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $property->delete();

        return redirect()->route('owner.properties.index')
            ->with('success', 'Kontrakan berhasil dihapus.');
    }
}
