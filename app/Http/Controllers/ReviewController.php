<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }
    public function create()
    {
        return view('reviews.create');
    }
    public function store(Request $request)
    {
        // Simpan review baru
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
        // Kemaskini review
    }
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $review->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review dipadam.');
    }
}
