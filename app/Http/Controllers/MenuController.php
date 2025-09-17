<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of menu items for a specific shop.
     */
    public function index(Request $request, $shopId = null)
    {
        $user = Auth::user();

        if ($shopId) {
            // Show menu for a specific shop
            $shop = Shop::findOrFail($shopId);
            $query = MenuItem::where('shop_id', $shopId);
        } else {
            // Show menu items for vendor's shops
            if ($user->role !== 'vendor') {
                abort(403, 'Akses tidak dibenarkan.');
            }

            $shopIds = $user->shops()->pluck('id');
            $query = MenuItem::whereIn('shop_id', $shopIds);
            $shop = null;
        }

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('available')) {
            $query->where('is_available', $request->boolean('available'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $menuItems = $query->with('shop')
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(15);

        $categories = MenuItem::getCategories();

        return view('menu.index', compact('menuItems', 'shop', 'categories'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $shops = $user->shops()->where('category', 'Makanan')->get();

        if ($shops->isEmpty()) {
            return redirect()->route('shops.create')
                ->with('error', 'Anda perlu buat kedai makanan terlebih dahulu.');
        }

        $shopId = $request->get('shop_id');
        $selectedShop = $shopId ? $shops->find($shopId) : $shops->first();

        $categories = MenuItem::getCategories();

        return view('menu.create', compact('shops', 'selectedShop', 'categories'));
    }

    /**
     * Store a newly created menu item in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'required|integer|min:1|max:120',
            'is_available' => 'boolean',
            'is_vegetarian' => 'boolean',
            'is_spicy' => 'boolean',
            'is_halal' => 'boolean',
            'customization_options' => 'nullable|json'
        ]);

        // Verify shop ownership
        $shop = Shop::findOrFail($request->shop_id);
        if ($shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-items', 'public');
        }

        MenuItem::create([
            'shop_id' => $request->shop_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath,
            'preparation_time' => $request->preparation_time,
            'is_available' => $request->boolean('is_available', true),
            'is_vegetarian' => $request->boolean('is_vegetarian'),
            'is_spicy' => $request->boolean('is_spicy'),
            'is_halal' => $request->boolean('is_halal', true),
            'customization_options' => $request->customization_options
        ]);

        return redirect()->route('menu.index')
            ->with('success', 'Item menu berjaya ditambah!');
    }

    /**
     * Display the specified menu item.
     */
    public function show(MenuItem $menuItem)
    {
        $this->authorize('view', $menuItem);

        return view('menu.show', compact('menuItem'));
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit(MenuItem $menuItem)
    {
        $this->authorize('update', $menuItem);

        $user = Auth::user();
        $shops = $user->shops()->where('category', 'Makanan')->get();
        $categories = MenuItem::getCategories();

        return view('menu.edit', compact('menuItem', 'shops', 'categories'));
    }

    /**
     * Update the specified menu item in storage.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $this->authorize('update', $menuItem);

        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'required|integer|min:1|max:120',
            'is_available' => 'boolean',
            'is_vegetarian' => 'boolean',
            'is_spicy' => 'boolean',
            'is_halal' => 'boolean',
            'customization_options' => 'nullable|json'
        ]);

        $user = Auth::user();

        // Verify shop ownership
        $shop = Shop::findOrFail($request->shop_id);
        if ($shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        // Handle image upload
        $imagePath = $menuItem->image; // Keep existing image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $imagePath = $request->file('image')->store('menu-items', 'public');
        }

        $menuItem->update([
            'shop_id' => $request->shop_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath,
            'preparation_time' => $request->preparation_time,
            'is_available' => $request->boolean('is_available', true),
            'is_vegetarian' => $request->boolean('is_vegetarian'),
            'is_spicy' => $request->boolean('is_spicy'),
            'is_halal' => $request->boolean('is_halal', true),
            'customization_options' => $request->customization_options
        ]);

        return redirect()->route('menu.index')
            ->with('success', 'Item menu berjaya dikemaskini!');
    }

    /**
     * Toggle availability of the specified menu item.
     */
    public function toggleAvailability(MenuItem $menuItem)
    {
        $this->authorize('update', $menuItem);

        $menuItem->update([
            'is_available' => !$menuItem->is_available
        ]);

        $status = $menuItem->is_available ? 'disediakan' : 'tidak disediakan';

        return redirect()->back()
            ->with('success', "Item menu kini {$status}.");
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        $this->authorize('delete', $menuItem);

        // Delete image if exists
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return redirect()->route('menu.index')
            ->with('success', 'Item menu berjaya dipadam!');
    }
}
