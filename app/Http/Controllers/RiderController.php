<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rider::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by availability
        if ($request->has('availability') && $request->availability !== '') {
            $query->where('availability', $request->availability);
        }

        // Filter by vehicle type
        if ($request->has('vehicle_type') && $request->vehicle_type !== '') {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // Search by name or phone
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $riders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('riders.index', compact('riders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('riders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:riders,phone',
            'email' => 'required|email|max:255|unique:riders,email',
            'ic_number' => 'required|string|max:20|unique:riders,ic_number',
            'vehicle_type' => 'required|in:motorcycle,car,bicycle,walking',
            'vehicle_number' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Rider::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'ic_number' => $request->ic_number,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'address' => $request->address,
            'status' => $request->status,
            'availability' => 'available',
            'rating' => 0,
            'total_deliveries' => 0,
        ]);

        return redirect()->route('riders.index')
            ->with('success', 'Rider berjaya ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rider $rider)
    {
        $rider->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('riders.show', compact('rider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rider $rider)
    {
        return view('riders.edit', compact('rider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rider $rider)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', Rule::unique('riders')->ignore($rider->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('riders')->ignore($rider->id)],
            'ic_number' => ['required', 'string', 'max:20', Rule::unique('riders')->ignore($rider->id)],
            'vehicle_type' => 'required|in:motorcycle,car,bicycle,walking',
            'vehicle_number' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive,suspended',
            'availability' => 'required|in:available,busy,offline',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rider->update($request->only([
            'name', 'phone', 'email', 'ic_number', 'vehicle_type',
            'vehicle_number', 'address', 'status', 'availability'
        ]));

        return redirect()->route('riders.index')
            ->with('success', 'Rider berjaya dikemaskini!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rider $rider)
    {
        // Check if rider has active orders
        if ($rider->activeOrders()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak boleh padam rider yang mempunyai pesanan aktif!');
        }

        $rider->delete();

        return redirect()->route('riders.index')
            ->with('success', 'Rider berjaya dipadam!');
    }

    /**
     * Update rider availability
     */
    public function updateAvailability(Request $request, Rider $rider)
    {
        $validator = Validator::make($request->all(), [
            'availability' => 'required|in:available,busy,offline',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid availability status'], 400);
        }

        $rider->update(['availability' => $request->availability]);

        return response()->json([
            'success' => true,
            'message' => 'Availability updated successfully',
            'availability' => $rider->availability
        ]);
    }

    /**
     * Update rider location
     */
    public function updateLocation(Request $request, Rider $rider)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid coordinates'], 400);
        }

        $rider->updateLocation($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully'
        ]);
    }

    /**
     * Get available riders for assignment
     */
    public function getAvailableRiders(Request $request)
    {
        $query = Rider::available();

        // Filter by vehicle type if specified
        if ($request->has('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // Filter by location if coordinates provided
        if ($request->has('latitude') && $request->has('longitude')) {
            $userLat = $request->latitude;
            $userLng = $request->longitude;

            $riders = $query->get()->map(function($rider) use ($userLat, $userLng) {
                $rider->distance = $rider->getDistanceFromCoordinates($userLat, $userLng);
                return $rider;
            })->sortBy('distance')->take(10);
        } else {
            $riders = $query->take(10)->get();
        }

        return response()->json([
            'riders' => $riders->map(function($rider) {
                return [
                    'id' => $rider->id,
                    'name' => $rider->name,
                    'phone' => $rider->phone,
                    'vehicle_type' => $rider->vehicle_type,
                    'vehicle_type_text' => $rider->vehicle_type_text,
                    'rating' => $rider->rating,
                    'total_deliveries' => $rider->total_deliveries,
                    'distance' => $rider->distance ?? null,
                ];
            })
        ]);
    }

    /**
     * Get rider statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total_riders' => Rider::count(),
            'active_riders' => Rider::where('status', 'active')->count(),
            'available_riders' => Rider::available()->count(),
            'busy_riders' => Rider::where('availability', 'busy')->count(),
            'average_rating' => Rider::where('total_deliveries', '>', 0)->avg('rating'),
        ];

        return response()->json($stats);
    }
}
