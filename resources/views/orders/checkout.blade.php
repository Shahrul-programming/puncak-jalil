@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Checkout</h1>
            <p class="text-gray-600 dark:text-gray-400">Selesaikan pesanan dari {{ $shop->name }}</p>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Items -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Item Pesanan</h2>

                        <div class="space-y-4">
                            @foreach($items as $index => $item)
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    @if($item['menu_item']->image)
                                        <img src="{{ $item['menu_item']->image_url }}"
                                             alt="{{ $item['menu_item']->name }}"
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-400 text-xl"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item['menu_item']->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item['menu_item']->description }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                RM {{ number_format($item['unit_price'], 2) }} x {{ $item['quantity'] }}
                                            </span>
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                RM {{ number_format($item['total_price'], 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs for order items -->
                                <input type="hidden" name="items[{{ $index }}][menu_item_id]" value="{{ $item['menu_item']->id }}">
                                <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                                <input type="hidden" name="items[{{ $index }}][special_requests]" value="{{ $item['special_requests'] }}">
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Arahan Khas</h2>
                        <textarea name="special_instructions" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Contoh: Jangan tambah bawang, pedas sedikit...">{{ old('special_instructions') }}</textarea>
                        @error('special_instructions')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Delivery & Payment Details -->
                <div class="space-y-6">
                    <!-- Delivery Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Maklumat Penghantaran</h2>

                        <div class="space-y-4">
                            <!-- Delivery Address -->
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Alamat Penghantaran *
                                </label>
                                <textarea name="delivery_address" id="delivery_address" rows="3" required
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                          placeholder="Masukkan alamat lengkap untuk penghantaran">{{ old('delivery_address', Auth::user()->address ?? '') }}</textarea>
                                @error('delivery_address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Delivery Time -->
                            <div>
                                <label for="requested_delivery_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Masa Penghantaran Dikehendaki
                                </label>
                                <input type="datetime-local" name="requested_delivery_time" id="requested_delivery_time"
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Biarkan kosong untuk penghantaran segera</p>
                                @error('requested_delivery_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Kaedah Pembayaran</h2>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="cash" value="cash"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                <label for="cash" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                                    Tunai (Bayar kepada rider)
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="online" value="online"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="online" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                    Kad Kredit/Debit (Online)
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="ewallet" value="ewallet"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="ewallet" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-mobile-alt mr-2 text-purple-600"></i>
                                    E-Wallet (Touch 'n Go, Boost, dll)
                                </label>
                            </div>
                        </div>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Ringkasan Pesanan</h2>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="font-semibold">RM {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Penghantaran:</span>
                                <span class="font-semibold">RM {{ number_format($deliveryFee, 2) }}</span>
                            </div>

                            @if($deliveryFee > 0)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    * Penghantaran percuma untuk pesanan melebihi RM50
                                </p>
                            @endif

                            <hr class="border-gray-200 dark:border-gray-700">

                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-900 dark:text-white">Jumlah:</span>
                                <span class="text-blue-600">RM {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        <input type="hidden" name="status" value="active">

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-semibold">
                            <i class="fas fa-shopping-cart mr-2"></i>Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('orders.create', ['shop_id' => $shop->id]) }}"
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Menu
                </a>
            </div>
        </form>
    </div>
</div>
@endsection