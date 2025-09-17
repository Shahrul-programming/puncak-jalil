@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                @if(Auth::user()->role === 'vendor')
                    Pesanan Kedai Saya
                @else
                    Pesanan Saya
                @endif
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                @if(Auth::user()->role === 'vendor')
                    Urus semua pesanan dari pelanggan
                @else
                    Lihat dan urus pesanan anda
                @endif
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-0">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pengesahan</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Disahkan</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Sedang Disediakan</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Dihantar</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="flex-1 min-w-0">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Dari Tarikh
                    </label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex-1 min-w-0">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Hingga Tarikh
                    </label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-search mr-2"></i>Tapis
                    </button>
                </div>
            </form>
        </div>

        <!-- Orders List -->
        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Pesanan #{{ $order->order_number }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    @if(Auth::user()->role === 'vendor')
                                        Pelanggan: {{ $order->user->name }}
                                    @else
                                        Kedai: {{ $order->shop->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                    @elseif($order->status === 'ready') bg-green-100 text-green-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $order->getStatusTextAttribute() }}
                                </span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Jumlah Item: {{ $order->orderItems->count() }}
                                    </p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        RM {{ number_format($order->total_amount, 2) }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('orders.show', $order) }}"
                                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                        Lihat
                                    </a>

                                    @if(Auth::user()->role === 'vendor' && $order->canBeCancelled())
                                        <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                                    onclick="return confirm('Adakah anda pasti mahu batalkan pesanan ini?')">
                                                Batal
                                            </button>
                                        </form>
                                    @endif

                                    @if(Auth::user()->role === 'user' && $order->isPending())
                                        <a href="{{ route('orders.edit', $order) }}"
                                           class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                            Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tiada Pesanan</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        @if(Auth::user()->role === 'vendor')
                            Belum ada pesanan dari pelanggan
                        @else
                            Anda belum membuat sebarang pesanan
                        @endif
                    </p>
                    @if(Auth::user()->role === 'user')
                        <a href="{{ route('orders.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Pesanan Baru
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection