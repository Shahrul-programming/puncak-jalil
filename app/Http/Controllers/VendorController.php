<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Rider;
use App\Models\MenuItem;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function __construct()
    {
        // Middleware will be applied at route level
    }

    /**
     * Vendor Dashboard
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Get vendor's shops
        $shops = Shop::where('user_id', $user->id)->get();

        if ($shops->isEmpty()) {
            return view('vendor.no-shop');
        }

        $shopIds = $shops->pluck('id');

        // Get today's orders
        $todayOrders = Order::whereIn('shop_id', $shopIds)
            ->whereDate('created_at', today())
            ->count();

        // Get pending orders
        $pendingOrders = Order::whereIn('shop_id', $shopIds)
            ->where('status', 'confirmed')
            ->count();

        // Get today's revenue
        $todayRevenue = Order::whereIn('shop_id', $shopIds)
            ->whereDate('created_at', today())
            ->where('status', 'delivered')
            ->sum('total_amount');

        // Get total orders this month
        $monthlyOrders = Order::whereIn('shop_id', $shopIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get recent orders
        $recentOrders = Order::with(['user', 'rider'])
            ->whereIn('shop_id', $shopIds)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get order status distribution
        $orderStatusStats = Order::whereIn('shop_id', $shopIds)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Get available riders count
        $availableRiders = Rider::available()->count();

        // Get menu items count
        $totalMenuItems = MenuItem::whereIn('shop_id', $shopIds)->count();

        return view('vendor.dashboard', compact(
            'shops',
            'todayOrders',
            'pendingOrders',
            'todayRevenue',
            'monthlyOrders',
            'recentOrders',
            'orderStatusStats',
            'availableRiders',
            'totalMenuItems'
        ));
    }

    /**
     * Vendor Orders Management
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::where('user_id', $user->id)->get();
        $shopIds = $shops->pluck('id');

        $query = Order::with(['user', 'shop', 'rider', 'orderItems'])
            ->whereIn('shop_id', $shopIds);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by shop
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by rider
        if ($request->filled('rider_id')) {
            $query->where('rider_id', $request->rider_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get available riders for assignment
        $availableRiders = Rider::available()->get();

        // Get all vendor's riders (for filtering)
        $allRiders = Rider::where('status', 'active')->get();

        return view('vendor.orders', compact('orders', 'shops', 'availableRiders', 'allRiders'));
    }

    /**
     * Show specific order for vendor
     */
    public function showOrder(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'shop', 'rider', 'orderItems.menuItem']);

        return view('vendor.order-detail', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,delivered'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);

            // Update rider availability if order is delivered
            if ($order->rider) {
                $order->rider->setAvailable();
            }
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
     * Assign rider to order
     */
    public function assignRider(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $request->validate([
            'rider_id' => 'required|exists:riders,id'
        ]);

        $rider = Rider::findOrFail($request->rider_id);

        if (!$rider->isAvailable()) {
            return redirect()->back()->with('error', 'Rider tidak tersedia untuk tugasan.');
        }

        if (!$order->canAssignRider()) {
            return redirect()->back()->with('error', 'Pesanan tidak boleh ditugaskan kepada rider.');
        }

        if ($order->assignRider($rider)) {
            return redirect()->back()->with('success', 'Rider berjaya ditugaskan kepada pesanan.');
        }

        return redirect()->back()->with('error', 'Gagal menugaskan rider kepada pesanan.');
    }

    /**
     * Unassign rider from order
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
     * Get available riders for assignment
     */
    public function getAvailableRiders(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::where('user_id', $user->id)->get();

        // Get shop coordinates for distance calculation
        $shopLat = $shops->first()->latitude ?? null;
        $shopLng = $shops->first()->longitude ?? null;

        $query = Rider::available();

        // Filter by vehicle type if specified
        if ($request->has('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        $riders = $query->get()->map(function($rider) use ($shopLat, $shopLng) {
            $rider->distance = ($shopLat && $shopLng) ? $rider->getDistanceFromCoordinates($shopLat, $shopLng) : null;
            return $rider;
        })->sortBy('distance')->take(20);

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
                    'distance' => $rider->distance,
                ];
            })
        ]);
    }

    /**
     * Vendor Menu Management
     */
    public function menu(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::where('user_id', $user->id)->get();
        $shopIds = $shops->pluck('id');

        $query = MenuItem::with('shop')->whereIn('shop_id', $shopIds);

        // Filter by shop
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // Filter by availability
        if ($request->filled('availability')) {
            $query->where('availability', $request->availability === 'available');
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menuItems = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('vendor.menu', compact('menuItems', 'shops'));
    }

    /**
     * Vendor Reports
     */
    public function reports(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::where('user_id', $user->id)->get();
        $shopIds = $shops->pluck('id');

        // Date range
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Revenue statistics
        $revenueStats = Order::whereIn('shop_id', $shopIds)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Order status distribution
        $statusStats = Order::whereIn('shop_id', $shopIds)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Top selling items
        $topItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->whereIn('orders.shop_id', $shopIds)
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->select(
                'menu_items.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderBy('total_quantity', 'desc')
            ->take(10)
            ->get();

        return view('vendor.reports', compact(
            'shops',
            'revenueStats',
            'statusStats',
            'topItems',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Toggle menu item availability
     */
    public function toggleMenuAvailability(MenuItem $menuItem)
    {
        $this->authorize('update', $menuItem);

        $menuItem->update(['availability' => !$menuItem->availability]);

        return redirect()->back()->with('success',
            'Status ketersediaan menu berjaya dikemaskini: ' .
            ($menuItem->availability ? 'Tersedia' : 'Tidak Tersedia')
        );
    }
}
