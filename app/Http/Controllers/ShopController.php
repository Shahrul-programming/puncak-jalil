<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = \App\Models\Shop::all();
        return view('shops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = \App\Models\Shop::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('shops.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shop = \App\Models\Shop::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $shop->update($request->only(['name','category','address','status']));
        return redirect()->route('shops.index')->with('success', 'Kedai berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = \App\Models\Shop::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $shop->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $shop->delete();
        return redirect()->route('shops.index')->with('success', 'Kedai berjaya dipadam.');
    }
}
