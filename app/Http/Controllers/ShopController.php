<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shop::with(['user', 'reviews']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Rating filter
        if ($request->filled('rating')) {
            $minRating = (float) $request->rating;
            $query->whereHas('reviews', function($q) use ($minRating) {
                $q->havingRaw('AVG(rating) >= ?', [$minRating]);
            });
        }
        
        // Status filter (admin only)
        if ($request->filled('status') && Auth::check() && Auth::user()->role === 'admin') {
            $query->where('status', $request->status);
        } else {
            // Non-admin users only see active shops
            $query->where('status', 'active');
        }
        
        // Open now filter
        if ($request->filled('open_now') && $request->open_now == '1') {
            $currentTime = now()->format('H:i:s');
            $query->where('opening_time', '<=', $currentTime)
                  ->where('closing_time', '>=', $currentTime);
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'rating':
                $query->leftJoin('reviews', 'shops.id', '=', 'reviews.shop_id')
                      ->select('shops.*')
                      ->groupBy('shops.id')
                      ->orderByRaw('AVG(reviews.rating) DESC NULLS LAST');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }
        
        $shops = $query->paginate(12)->withQueryString();
        
        // Get filter options for dropdowns
        $categories = Shop::distinct()->pluck('category')->filter()->sort();
        // States feature disabled for now - no state column exists
        $states = collect();
        
        // Get all shops with coordinates for map view (without pagination)
        $shopsForMap = Shop::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'name', 'category', 'address', 'latitude', 'longitude')
            ->get();
        
        return view('shops.index', compact('shops', 'categories', 'states', 'shopsForMap'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'opening_hours' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive'
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('shops', 'public');
        }

        $shop = Shop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imagePath,
            'address' => $request->address,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'website' => $request->website,
            'opening_hours' => $request->opening_hours,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

        return redirect()->route('shops.index')->with('success', 'Kedai berjaya ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        $shop->load(['user', 'reviews.user', 'promotions']);
        
        $averageRating = $shop->average_rating;
        $reviewCount = $shop->review_count;
        
        return view('shops.show', compact('shop', 'averageRating', 'reviewCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('shops.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'opening_hours' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive'
        ]);

        // Handle image upload
        $imagePath = $shop->image; // Keep existing image if no new upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($shop->image) {
                \Storage::disk('public')->delete($shop->image);
            }
            $imagePath = $request->file('image')->store('shops', 'public');
        }

        $shop->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imagePath,
            'address' => $request->address,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'website' => $request->website,
            'opening_hours' => $request->opening_hours,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

        return redirect()->route('shops.index')->with('success', 'Kedai berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $shop->delete();
        return redirect()->route('shops.index')->with('success', 'Kedai berjaya dipadam.');
    }
}
