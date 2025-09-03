
@extends('layouts.app')

@section('head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Direktori Kedai</h1>
            <p class="text-gray-600 dark:text-gray-400">Temui kedai dan perniagaan di Puncak Jalil</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
            <!-- View Toggle -->
            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <button id="listViewBtn" class="px-4 py-2 rounded-md text-sm font-medium transition-colors bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    List
                </button>
                <button id="mapViewBtn" class="px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"/>
                    </svg>
                    Map
                </button>
            </div>
            @auth
                <a href="{{ route('shops.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kedai
                </a>
            @endauth
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('shops.index') }}" id="searchForm">
            <!-- Main Search Row -->
            <div class="flex flex-col lg:flex-row gap-4 mb-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama kedai, kategori, atau alamat..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                
                <!-- Search Button -->
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- State Filter - Disabled (no state column exists) -->
                {{-- 
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Negeri</label>
                    <select name="state" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Semua Negeri</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                </div>
                --}}

                <!-- Rating Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Rating</label>
                    <select name="rating" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Semua Rating</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4★ ke atas</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3★ ke atas</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2★ ke atas</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1★ ke atas</option>
                    </select>
                </div>

                <!-- Open Now Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="open_now" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Semua</option>
                        <option value="1" {{ request('open_now') == '1' ? 'selected' : '' }}>Buka Sekarang</option>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Susun</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $shops->total() }} kedai ditemui
                    @if(request()->hasAny(['search', 'category', 'rating', 'open_now']))
                        <span class="ml-2">
                            <a href="{{ route('shops.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear filters
                            </a>
                        </span>
                    @endif
                </div>
                
                <!-- Active Filters Display -->
                @if(request()->hasAny(['search', 'category', 'rating', 'open_now']))
                    <div class="flex flex-wrap gap-2">
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                            </span>
                        @endif
                        @if(request('category'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ request('category') }}
                                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                            </span>
                        @endif
                        {{-- State filter disabled
                        @if(request('state'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                {{ request('state') }}
                                <a href="{{ request()->fullUrlWithQuery(['state' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                            </span>
                        @endif
                        --}}
                        @if(request('rating'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                {{ request('rating') }}★+
                                <a href="{{ request()->fullUrlWithQuery(['rating' => null]) }}" class="ml-1 text-yellow-600 hover:text-yellow-800">×</a>
                            </span>
                        @endif
                        @if(request('open_now'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                Buka Sekarang
                                <a href="{{ request()->fullUrlWithQuery(['open_now' => null]) }}" class="ml-1 text-emerald-600 hover:text-emerald-800">×</a>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </form>
    </div>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            
            <!-- Search Button -->
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-gray-600 dark:text-gray-400">
            Menunjukkan {{ $shops->count() }} kedai
            @if(request('search') || request('category') || request('status'))
                daripada {{ $shops->total() ?? $shops->count() }} jumlah kedai
            @endif
        </p>
    </div>

    <!-- Map View -->
    <div id="mapView" class="hidden mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Lokasi Kedai</h2>
            <div id="shopsMap" style="height: 500px;" class="rounded-lg overflow-hidden"></div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p>📍 Klik pada pin untuk melihat maklumat kedai. Total {{ $shopsForMap->count() }} kedai dengan koordinat.</p>
            </div>
        </div>
    </div>

    <!-- List View -->
    <div id="listView">
        <!-- Shops Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($shops as $shop)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Shop Image -->
                <div class="h-48 relative overflow-hidden">
                    @if($shop->image)
                        <img src="{{ Storage::url($shop->image) }}" alt="{{ $shop->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    <!-- Shop Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $shop->name }}</h3>
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 rounded-full">
                                {{ $shop->category }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shop->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $shop->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <!-- Description -->
                    @if($shop->description)
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $shop->description }}</p>
                    @endif

                    <!-- Address -->
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="line-clamp-1">{{ $shop->address }}</span>
                    </div>

                    <!-- Rating and Reviews -->
                    <div class="flex items-center gap-2 mb-4">
                        @if($shop->review_count > 0)
                            <div class="flex items-center gap-1">
                                @foreach($shop->rating_stars as $star)
                                    @if($star === 'full')
                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @elseif($star === 'half')
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half-star-{{ $shop->id }}">
                                                    <stop offset="50%" stop-color="currentColor"/>
                                                    <stop offset="50%" stop-color="#e5e7eb"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half-star-{{ $shop->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endforeach
                                <span class="text-sm font-medium text-gray-900 dark:text-white ml-1">{{ $shop->average_rating }}</span>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                ({{ $shop->review_count }} {{ $shop->review_count == 1 ? 'ulasan' : 'ulasan' }})
                            </span>
                        @else
                            <div class="flex items-center text-gray-400 dark:text-gray-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">Tiada ulasan</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contact Info -->
                    <div class="flex items-center gap-4 mb-4">
                        @if($shop->phone)
                            <a href="tel:{{ $shop->phone }}" class="flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Call
                            </a>
                        @endif
                        @if($shop->whatsapp)
                            <a href="{{ $shop->formatted_whatsapp_link }}" target="_blank" class="flex items-center gap-1 text-green-600 hover:text-green-700 text-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"></path>
                                </svg>
                                WhatsApp
                            </a>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('shops.show', $shop->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                            Lihat Detail
                        </a>
                        
                        @php $user = auth()->user(); @endphp
                        @if($user && ($user->role === 'admin' || $shop->user_id === $user->id))
                            <a href="{{ route('shops.edit', $shop->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('shops.destroy', $shop->id) }}" method="POST" class="inline" onsubmit="return confirm('Padam kedai ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                    Padam
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tiada kedai dijumpai</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if(request('search') || request('category') || request('status'))
                            Cuba ubah kriteria pencarian anda.
                        @else
                            Belum ada kedai yang didaftarkan.
                        @endif
                    </p>
                    @auth
                        @if(!request('search') && !request('category') && !request('status'))
                            <div class="mt-6">
                                <a href="{{ route('shops.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Kedai Pertama
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($shops, 'links'))
        <div class="mt-8">
            {{ $shops->links() }}
        </div>
    @endif
    </div> <!-- End List View -->
</div>

<!-- Custom CSS for line-clamp -->
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle functionality
    const listViewBtn = document.getElementById('listViewBtn');
    const mapViewBtn = document.getElementById('mapViewBtn');
    const listView = document.getElementById('listView');
    const mapView = document.getElementById('mapView');
    let map = null;
    let markersInitialized = false;

    // Toggle to List View
    listViewBtn.addEventListener('click', function() {
        listView.classList.remove('hidden');
        mapView.classList.add('hidden');
        
        // Update button styles
        listViewBtn.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
        listViewBtn.classList.remove('text-gray-500', 'dark:text-gray-400');
        mapViewBtn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
        mapViewBtn.classList.add('text-gray-500', 'dark:text-gray-400');
    });

    // Toggle to Map View
    mapViewBtn.addEventListener('click', function() {
        listView.classList.add('hidden');
        mapView.classList.remove('hidden');
        
        // Update button styles
        mapViewBtn.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
        mapViewBtn.classList.remove('text-gray-500', 'dark:text-gray-400');
        listViewBtn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
        listViewBtn.classList.add('text-gray-500', 'dark:text-gray-400');

        // Initialize map if not already done
        if (!map) {
            initializeMap();
        }
    });

    // Initialize Map
    function initializeMap() {
        // Default center (Puncak Jalil area)
        const defaultLat = 3.0478;
        const defaultLng = 101.7089;
        
        map = L.map('shopsMap').setView([defaultLat, defaultLng], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add markers for all shops
        const shopsData = @json($shopsForMap);
        const markers = [];

        shopsData.forEach(function(shop) {
            if (shop.latitude && shop.longitude) {
                // Custom icon based on category
                const categoryColors = {
                    'restaurant': '#ef4444',
                    'retail': '#3b82f6',
                    'service': '#10b981',
                    'healthcare': '#f59e0b',
                    'education': '#8b5cf6',
                    'automotive': '#6b7280',
                    'default': '#6366f1'
                };

                const color = categoryColors[shop.category.toLowerCase()] || categoryColors.default;

                const shopIcon = L.divIcon({
                    className: 'custom-shop-marker',
                    html: `
                        <div class="text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg border-2 border-white" style="background-color: ${color}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    `,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                const marker = L.marker([shop.latitude, shop.longitude], { icon: shopIcon }).addTo(map);

                // Create popup content
                const popupContent = `
                    <div class="p-3 min-w-64">
                        <h3 class="font-semibold text-lg text-gray-900 mb-1">${shop.name}</h3>
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium mb-2">
                            ${shop.category}
                        </span>
                        <p class="text-sm text-gray-600 mb-3">${shop.address}</p>
                        <div class="flex space-x-2">
                            <a href="/shops/${shop.id}" 
                               class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                                View Details
                            </a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${shop.latitude},${shop.longitude}" 
                               target="_blank" 
                               class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition-colors">
                                Directions
                            </a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                markers.push(marker);
            }
        });

        // Fit map to show all markers if there are any
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        markersInitialized = true;
    }

    // Auto-submit form when filter values change (except search)
    const form = document.getElementById('searchForm');
    const filterInputs = form.querySelectorAll('select[name]:not([name="search"])');
    
    filterInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Real-time search with debounce
    const searchInput = form.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                if (searchInput.value.length >= 3 || searchInput.value.length === 0) {
                    form.submit();
                }
            }, 500); // Debounce for 500ms
        });
    }
});
</script>
@endsection

