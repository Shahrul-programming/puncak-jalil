@extends('layouts.app')

@section('title', 'Pengurusan Menu Vendor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengurusan Menu</h1>
            <p class="text-gray-600 mt-2">Urus item menu dari kedai anda</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('vendor.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Dashboard
            </a>
            <a href="{{ route('menu.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-plus mr-2"></i>Tambah Menu
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('vendor.menu') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @if($shops->count() > 1)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kedai</label>
                <select name="shop_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kedai</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ketersediaan</label>
                <select name="availability" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Carian</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama menu..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('vendor.menu') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Menu Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($menuItems as $item)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Menu Image -->
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                     class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                </div>
            @endif

            <div class="p-6">
                <!-- Menu Info -->
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($item->availability) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        {{ $item->availability ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>

                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item->description }}</p>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-2xl font-bold text-gray-900">RM{{ number_format($item->price, 2) }}</span>
                    <span class="text-sm text-gray-500">{{ $item->category }}</span>
                </div>

                <!-- Shop Info -->
                <div class="text-sm text-gray-600 mb-4">
                    <i class="fas fa-store mr-1"></i>{{ $item->shop->name }}
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('menu.show', $item) }}"
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium text-center">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </a>
                    <a href="{{ route('menu.edit', $item) }}"
                       class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md text-sm font-medium text-center">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>

                <!-- Toggle Availability -->
                <form method="POST" action="{{ route('vendor.menu.toggle-availability', $item) }}" class="mt-3">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full {{ $item->availability ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-{{ $item->availability ? 'times' : 'check' }} mr-1"></i>
                        {{ $item->availability ? 'Tidak Tersedia' : 'Tersedia' }}
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-utensils text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tiada Item Menu</h3>
                <p class="text-gray-600 mb-4">Anda belum menambah sebarang item menu.</p>
                <a href="{{ route('menu.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Menu Pertama
                </a>
            </div>
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

@endsection