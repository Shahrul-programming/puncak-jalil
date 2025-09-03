<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Promotion;
use App\Models\Shop;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Promotion::with(['shop.user']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        
        if ($user->role === 'admin') {
            $promotions = $query->latest()->paginate(15);
            $shops = Shop::where('status', 'active')->get();
        } else {
            // Show only user's shop promotions
            $promotions = $query->whereHas('shop', function($q) use ($user) {
                                      $q->where('user_id', $user->id);
                                  })
                                  ->latest()
                                  ->paginate(15);
            $shops = Shop::where('user_id', $user->id)->where('status', 'active')->get();
        }
        
        // Get statistics
        $stats = [
            'total' => $promotions->total(),
            'active' => Promotion::active()->count(),
            'expired' => Promotion::expired()->count(),
            'upcoming' => Promotion::upcoming()->count(),
        ];
        
        return view('promotions.index', compact('promotions', 'shops', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Get user's shops
        $shops = Shop::where('user_id', $user->id)->where('status', 'active')->get();
        
        if ($shops->isEmpty()) {
            return redirect()->route('shops.create')
                           ->with('error', 'Anda perlu memiliki kedai yang aktif untuk membuat promosi.');
        }
        
        return view('promotions.create', compact('shops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'discount_type' => 'required|in:percentage,fixed,buy_one_get_one,free_shipping',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|numeric|min:0|max:100',
            'discount_amount' => 'required_if:discount_type,fixed|nullable|numeric|min:0',
            'promo_code' => 'nullable|string|max:50|unique:promotions,promo_code',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string|max:2000',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,disabled',
            'is_featured' => 'boolean'
        ]);

        // Check if user owns the shop
        $shop = Shop::findOrFail($request->shop_id);
        if ($shop->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak dibenarkan membuat promosi untuk kedai ini.');
        }

        $data = $request->all();
        $data['used_count'] = 0;
        
        // Auto-generate promo code if not provided
        if (empty($data['promo_code']) && $request->discount_type !== 'buy_one_get_one') {
            $data['promo_code'] = strtoupper($shop->name . '_' . now()->format('Ymd') . '_' . rand(100, 999));
        }

        Promotion::create($data);

        return redirect()->route('promotions.index')
                        ->with('success', 'Promosi berjaya dicipta!');
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(['shop.user']);
        
        // Check similar promotions
        $relatedPromotions = Promotion::where('shop_id', $promotion->shop_id)
                                    ->where('id', '!=', $promotion->id)
                                    ->where('status', 'active')
                                    ->where('end_date', '>=', now())
                                    ->take(5)
                                    ->get();
        
        return view('promotions.show', compact('promotion', 'relatedPromotions'));
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $promotion->shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        
        $shops = Shop::where('user_id', $user->id)->where('status', 'active')->get();
        if ($user->role === 'admin') {
            $shops = Shop::where('status', 'active')->get();
        }
        
        return view('promotions.edit', compact('promotion', 'shops'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $promotion->shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'discount_type' => 'required|in:percentage,fixed,buy_one_get_one,free_shipping',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|numeric|min:0|max:100',
            'discount_amount' => 'required_if:discount_type,fixed|nullable|numeric|min:0',
            'promo_code' => 'nullable|string|max:50|unique:promotions,promo_code,' . $promotion->id,
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string|max:2000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,expired,disabled',
            'is_featured' => 'boolean'
        ]);

        $data = $request->all();

        $promotion->update($data);

        return redirect()->route('promotions.index')
                        ->with('success', 'Promosi berjaya dikemaskini!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $promotion->shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        
        $promotion->delete();
        
        return redirect()->route('promotions.index')
                        ->with('success', 'Promosi berjaya dipadam.');
    }

    // Additional methods for promotion management
    public function toggleStatus(Promotion $promotion)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $promotion->shop->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $newStatus = $promotion->status === 'active' ? 'disabled' : 'active';
        $promotion->update(['status' => $newStatus]);
        
        return response()->json([
            'success' => true,
            'status' => $promotion->status_display,
            'message' => 'Status promosi berjaya dikemaskini.'
        ]);
    }

    public function toggleFeatured(Promotion $promotion)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $promotion->update(['is_featured' => !$promotion->is_featured]);
        
        return response()->json([
            'success' => true,
            'is_featured' => $promotion->is_featured,
            'message' => 'Status featured berjaya dikemaskini.'
        ]);
    }

    // Public method to view active promotions
    public function publicIndex(Request $request)
    {
        $query = Promotion::with(['shop'])->active();
        
        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }
        
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        
        // Featured promotions first
        $query->orderBy('is_featured', 'desc')->latest();
        
        $promotions = $query->paginate(12);
        
        // Get featured promotions for banner
        $featuredPromotions = Promotion::with(['shop'])
                                     ->active()
                                     ->featured()
                                     ->limit(5)
                                     ->get();
        
        return view('promotions.public', compact('promotions', 'featuredPromotions'));
    }

    // Method to validate and use promo code
    public function validatePromoCode(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'shop_id' => 'required|exists:shops,id',
            'purchase_amount' => 'required|numeric|min:0'
        ]);

        $promotion = Promotion::where('promo_code', $request->promo_code)
                             ->where('shop_id', $request->shop_id)
                             ->first();

        if (!$promotion) {
            return response()->json([
                'valid' => false,
                'message' => 'Kod promosi tidak dijumpai.'
            ]);
        }

        if (!$promotion->canBeUsed()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kod promosi sudah tidak aktif atau sudah mencapai had penggunaan.'
            ]);
        }

        $discountAmount = $promotion->calculateDiscount($request->purchase_amount);

        if ($discountAmount <= 0) {
            $minPurchase = $promotion->minimum_purchase;
            $message = $minPurchase ? 
                "Pembelian minimum RM " . number_format($minPurchase, 2) . " diperlukan." :
                "Kod promosi tidak boleh digunakan untuk jumlah ini.";
                
            return response()->json([
                'valid' => false,
                'message' => $message
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'final_amount' => $request->purchase_amount - $discountAmount,
            'promotion' => [
                'title' => $promotion->title,
                'discount_display' => $promotion->discount_display,
                'terms_conditions' => $promotion->terms_conditions
            ]
        ]);
    }
}
