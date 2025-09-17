@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Pilih Kedai</h1>
            <p class="text-gray-600 dark:text-gray-400">Pilih kedai makanan untuk membuat pesanan</p>
        </div>

        <!-- Shops Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($shops as $shop)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $shop->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $shop->address }}
                                </p>
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-sm {{ $i <= $shop->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                            ({{ $shop->review_count }} ulasan)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if($shop->image)
                                <img src="{{ asset('storage/' . $shop->image) }}"
                                     alt="{{ $shop->name }}"
                                     class="w-16 h-16 object-cover rounded-lg ml-4">
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $shop->menu_items_count }} item menu
                                </p>
                            </div>
                            <a href="{{ route('orders.create', ['shop_id' => $shop->id]) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-store text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tiada Kedai Makanan</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Tiada kedai makanan yang tersedia buat masa ini
                    </p>
                    <a href="{{ route('shops.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-search mr-2"></i>Lihat Semua Kedai
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection