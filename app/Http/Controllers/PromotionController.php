<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return view('promotions.index', compact('promotions'));
    }
    public function create()
    {
        return view('promotions.create');
    }
    public function store(Request $request)
    {
        // Simpan promosi baru
    }
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $promotion->shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('promotions.edit', compact('promotion'));
    }
    public function update(Request $request, $id)
    {
        // Kemaskini promosi
    }
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $promotion->shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promosi dipadam.');
    }
}
