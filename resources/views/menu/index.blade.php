@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Pengurusan Menu</h1>
            <p class="text-gray-600 dark:text-gray-400">Urus item menu untuk kedai anda</p>
        </div>

        <!-- Action Buttons -->
        <div class="mb-6 flex flex-wrap gap-4">
            <a href="{{ route('menu.create') }}"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Item Menu
            </a>

            @if($shop)
                <a href="{{ route('shops.show', $shop) }}"
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-store mr-2"></i>Lihat Kedai
                </a>
            @endif
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                @if(!$shop)
                    <div class="flex-1 min-w-0">
                        <label for="shop_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kedai
                        </label>
                        <select name="shop_id" id="shop_id"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Semua Kedai</option>
                            @foreach(Auth::user()->shops()->where('category', 'Makanan')->get() as $userShop)
                                <option value="{{ $userShop->id }}" {{ request('shop_id') == $userShop->id ? 'selected' : '' }}>
                                    {{ $userShop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kategori
                    </label>
                    <select name="category" id="category"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $catKey => $catValue)
                            <option value="{{ $catKey }}" {{ request('category') == $catKey ? 'selected' : '' }}>
                                {{ $catValue }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-0">
                    <label for="available" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select name="available" id="available"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Tersedia</option>
                        <option value="0" {{ request('available') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>

                <div class="flex-1 min-w-0">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Carian
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau penerangan..."
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

        <!-- Menu Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($menuItems as $item)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="relative">
                        @if($item->image)
                            <img src="{{ $item->image_url }}"
                                 alt="{{ $item->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-utensils text-4xl text-gray-400"></i>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($item->is_available) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($item->is_available)
                                    <i class="fas fa-check mr-1"></i>Tersedia
                                @else
                                    <i class="fas fa-times mr-1"></i>Tidak Tersedia
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $item->name }}
                                </h3>
                                @if(!$shop)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        {{ $item->shop->name }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $item->formatted_price }}</span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                            {{ $item->description }}
                        </p>

                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $item->category }}
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->preparation_time_text }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-2 mb-4">
                            @if($item->is_vegetarian)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Vegetarian</span>
                            @endif
                            @if($item->is_spicy)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Pedas</span>
                            @endif
                            @if($item->is_halal)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Halal</span>
                            @endif
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('menu.edit', $item) }}"
                               class="flex-1 text-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>

                            <form method="POST" action="{{ route('menu.toggle-availability', $item) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-4 py-2 {{ $item->is_available ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg focus:ring-2 focus:ring-offset-2 transition-colors"
                                        title="{{ $item->is_available ? 'Jadikan tidak tersedia' : 'Jadikan tersedia' }}">
                                    <i class="fas {{ $item->is_available ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('menu.destroy', $item) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                        onclick="return confirm('Adakah anda pasti mahu padam item menu ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-utensils text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tiada Item Menu</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        @if($shop)
                            Kedai ini belum mempunyai sebarang item menu
                        @else
                            Anda belum menambah sebarang item menu
                        @endif
                    </p>
                    <a href="{{ route('menu.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Item Menu Pertama
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($menuItems->hasPages())
            <div class="mt-8">
                {{ $menuItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection