<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Shop;
use App\Models\Rider;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['shop', 'orderItems']);

        if ($user->role === 'vendor') {
            // Vendors see orders for their shops
            $query->whereHas('shop', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } else {
            // Customers see their own orders
            $query->where('user_id', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('orders.index', compact('orders'));
    }

        /**
     * Show the checkout form for the order.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        
        // Validate items and calculate totals
        $items = [];
        $subtotal = 0;
        
        foreach ($request->items as $itemData) {
            $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);
            
            if (!$menuItem->is_available || $menuItem->shop_id !== $shop->id) {
                return back()->with('error', "Item {$menuItem->name} tidak tersedia");
            }
            
            $quantity = $itemData['quantity'];
            $unitPrice = $menuItem->price;
            $totalPrice = $unitPrice * $quantity;
            $subtotal += $totalPrice;
            
            $items[] = [
                'menu_item' => $menuItem,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'special_requests' => $itemData['special_requests'] ?? null,
            ];
        }
        
        $deliveryFee = $subtotal > 50 ? 0 : 5.00;
        $total = $subtotal + $deliveryFee;
        
        return view('orders.checkout', compact('shop', 'items', 'subtotal', 'deliveryFee', 'total'));
    }
    public function create(Request $request, $shopId = null)
    {
        if ($shopId) {
            $shop = Shop::with(['menuItems' => function($query) {
                $query->available()->orderBy('category')->orderBy('name');
            }])->findOrFail($shopId);

            return view('orders.create', compact('shop'));
        }

        // Show shop selection if no shop specified
        $shops = Shop::active()
            ->where('category', 'Makanan')
            ->withCount('menuItems')
            ->having('menu_items_count', '>', 0)
            ->get();

        return view('orders.shop-selection', compact('shops'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.special_requests' => 'nullable|string|max:500',
            'delivery_address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,online,ewallet',
            'requested_delivery_time' => 'nullable|date|after:now'
        ]);

        DB::transaction(function () use ($request) {
            $shop = Shop::findOrFail($request->shop_id);
            $subtotal = 0;
            $orderItems = [];

            // Calculate subtotal and prepare order items
            foreach ($request->items as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);

                if (!$menuItem->is_available || $menuItem->shop_id !== $shop->id) {
                    throw new \Exception("Menu item {$menuItem->name} is not available");
                }

                $quantity = $itemData['quantity'];
                $unitPrice = $menuItem->price;
                $totalPrice = $unitPrice * $quantity;

                $subtotal += $totalPrice;

                $orderItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'special_requests' => $itemData['special_requests'] ?? null,
                ];
            }

            // Calculate delivery fee (simple logic - can be enhanced)
            $deliveryFee = $subtotal > 50 ? 0 : 5.00; // Free delivery over RM50
            $totalAmount = $subtotal + $deliveryFee;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'paid',
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
                'requested_delivery_time' => $request->requested_delivery_time,
                'estimated_delivery_time' => now()->addMinutes(45), // Default 45 minutes
            ]);

            // Create order items
            foreach ($orderItems as $itemData) {
                $order->orderItems()->create($itemData);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan berjaya dibuat!');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['shop', 'orderItems.menuItem', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        // Only allow editing if order is still pending
        if (!$order->isPending()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan tidak boleh diubah setelah disahkan.');
        }

        $order->load(['shop.menuItems' => function($query) {
            $query->available()->orderBy('category')->orderBy('name');
        }, 'orderItems']);

        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if (!$order->isPending()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan tidak boleh diubah setelah disahkan.');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.special_requests' => 'nullable|string|max:500',
            'delivery_address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'requested_delivery_time' => 'nullable|date|after:now'
        ]);

        DB::transaction(function () use ($request, $order) {
            $subtotal = 0;
            $orderItems = [];

            // Calculate new subtotal and prepare order items
            foreach ($request->items as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);

                if (!$menuItem->is_available || $menuItem->shop_id !== $order->shop_id) {
                    throw new \Exception("Menu item {$menuItem->name} is not available");
                }

                $quantity = $itemData['quantity'];
                $unitPrice = $menuItem->price;
                $totalPrice = $unitPrice * $quantity;

                $subtotal += $totalPrice;

                $orderItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'special_requests' => $itemData['special_requests'] ?? null,
                ];
            }

            // Calculate delivery fee
            $deliveryFee = $subtotal > 50 ? 0 : 5.00;
            $totalAmount = $subtotal + $deliveryFee;

            // Update order
            $order->update([
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
                'requested_delivery_time' => $request->requested_delivery_time,
            ]);

            // Delete existing order items and create new ones
            $order->orderItems()->delete();
            foreach ($orderItems as $itemData) {
                $order->orderItems()->create($itemData);
            }
        });

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berjaya dikemaskini!');
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if (!$order->canBeCancelled()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan tidak boleh dibatalkan pada status ini.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berjaya dibatalkan.');
    }

    /**
     * Update order status (for vendors).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,delivered'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        // Send notification to customer if status changed
        if ($oldStatus !== $request->status) {
            $preferences = $order->user->notificationPreference ?? \App\Models\NotificationPreference::getForUser($order->user->id);

            // Check if user wants order status update notifications
            if ($preferences->wantsOrderStatusUpdates() && $preferences->shouldSendImmediately()) {
                $order->user->notify(new OrderStatusUpdated($order, $oldStatus, $request->status));
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berjaya dikemaskini.');
    }

    /**
     * Assign a rider to an order.
     */
    public function assignRider(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $request->validate([
            'rider_id' => 'required|exists:riders,id'
        ]);

        $rider = Rider::findOrFail($request->rider_id);

        // Check if rider is available
        if (!$rider->isAvailable()) {
            return redirect()->back()->with('error', 'Rider tidak tersedia untuk tugasan.');
        }

        // Check if order can be assigned a rider
        if (!$order->canAssignRider()) {
            return redirect()->back()->with('error', 'Pesanan tidak boleh ditugaskan kepada rider.');
        }

        // Assign rider to order
        if ($order->assignRider($rider)) {
            return redirect()->back()->with('success', 'Rider berjaya ditugaskan kepada pesanan.');
        }

        return redirect()->back()->with('error', 'Gagal menugaskan rider kepada pesanan.');
    }

    /**
     * Unassign rider from an order.
     */
    public function unassignRider(Order $order)
    {
        $this->authorize('updateStatus', $order);

        if ($order->unassignRider()) {
            return redirect()->back()->with('success', 'Rider berjaya ditarik balik dari pesanan.');
        }

        return redirect()->back()->with('error', 'Gagal menarik balik rider dari pesanan.');
    }

    /**
     * Get available riders for assignment (API endpoint).
     */
    public function getAvailableRiders(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $riders = Rider::available()->get();

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
                ];
            })
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        // Only allow deletion of cancelled orders
        if (!$order->isCancelled()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Hanya pesanan yang dibatalkan boleh dipadam.');
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berjaya dipadam.');
    }
}
