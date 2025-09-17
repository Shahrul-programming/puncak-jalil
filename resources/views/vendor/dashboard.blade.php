@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Vendor</h1>
        <p class="text-gray-600 mt-2">Selamat datang ke panel pengurusan vendor Puncak Jalil</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesanan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesanan Menunggu</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">RM{{ number_format($todayRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesanan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyOrders }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Order Management -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pengurusan Pesanan</h2>
            <div class="space-y-3">
                <a href="{{ route('vendor.orders') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-list text-blue-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-blue-900">Lihat Semua Pesanan</p>
                        <p class="text-sm text-blue-700">Urus dan kemaskini status pesanan</p>
                    </div>
                </a>
                <a href="{{ route('vendor.menu') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-utensils text-green-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-green-900">Urus Menu</p>
                        <p class="text-sm text-green-700">Tambah dan edit item menu</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Shop Management -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pengurusan Kedai</h2>
            <div class="space-y-3">
                @if($shops->count() > 0)
                    @foreach($shops as $shop)
                        <a href="{{ route('shops.edit', $shop) }}" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-store text-indigo-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-indigo-900">{{ $shop->name }}</p>
                                <p class="text-sm text-indigo-700">Edit maklumat kedai</p>
                            </div>
                        </a>
                    @endforeach
                @endif
                <a href="{{ route('shops.create') }}" class="flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    <i class="fas fa-plus text-red-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-red-900">Tambah Kedai Baru</p>
                        <p class="text-sm text-red-700">Daftar kedai tambahan</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Rider Management -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pengurusan Rider</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-motorcycle text-gray-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Rider Tersedia</p>
                            <p class="text-sm text-gray-600">{{ $availableRiders }} rider aktif</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('riders.index') }}" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                    <i class="fas fa-users text-orange-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-orange-900">Lihat Semua Rider</p>
                        <p class="text-sm text-orange-700">Urus maklumat rider</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Reports & Analytics -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Laporan & Analitik</h2>
            <div class="space-y-3">
                <a href="{{ route('vendor.reports') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="fas fa-chart-bar text-purple-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-purple-900">Lihat Laporan</p>
                        <p class="text-sm text-purple-700">Analisis prestasi perniagaan</p>
                    </div>
                </a>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-th-large text-gray-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Jumlah Menu</p>
                            <p class="text-sm text-gray-600">{{ $totalMenuItems }} item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Pesanan Terkini</h2>
            <a href="{{ route('vendor.orders') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->user->phone ?? 'Tiada nombor' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($order->status == 'pending') bg-gray-100 text-gray-800
                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                                    @elseif($order->status == 'ready') bg-green-100 text-green-800
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $order->status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->rider)
                                    <div class="text-sm text-gray-900">{{ $order->rider->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->rider->phone }}</div>
                                @else
                                    <span class="text-sm text-gray-500">Tiada rider</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                RM{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('vendor.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Tiada pesanan terkini</p>
            </div>
        @endif
    </div>
</div>

@endsection