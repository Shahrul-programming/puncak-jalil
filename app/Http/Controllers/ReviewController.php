<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\ShopReviewReceived;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $reviews = Review::with(['shop', 'user'])
                        ->latest()
                        ->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Check if user already reviewed this shop
        $existingReview = Review::where('shop_id', $request->shop_id)
                              ->where('user_id', Auth::id())
                              ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk kedai ini.');
        }

        $shop = Shop::findOrFail($request->shop_id);
        
        $review = Review::create([
            'shop_id' => $request->shop_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Send notification to shop owner and admins
        $shopOwner = $shop->user; // Assuming shops have user relationship
        $admins = User::where('role', 'admin')->get();
        $notifiableUsers = collect([$shopOwner])->merge($admins)->filter();

        foreach ($notifiableUsers as $user) {
            if ($user && $user->id !== Auth::id()) {
                $user->notify(new ShopReviewReceived($review, $shop, Auth::user()));
            }
        }

        return back()->with('success', 'Review berjaya ditambah!');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $review->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review = Review::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $review->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('shops.show', $review->shop_id)
                        ->with('success', 'Review berjaya dikemaskini!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $review->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        
        $shopId = $review->shop_id;
        $review->delete();
        
        return redirect()->route('shops.show', $shopId)
                        ->with('success', 'Review berjaya dipadam.');
    }
}
