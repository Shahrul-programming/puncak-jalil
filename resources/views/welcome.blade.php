<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Puncak Jalil Community Hub') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900">
    @php
        $siteSetting = \App\Models\SiteSetting::first();
        $backgroundColor = $siteSetting && $siteSetting->background_color ? $siteSetting->background_color : '';
    @endphp
    
    <div class="min-h-screen flex flex-col justify-between {{ $backgroundColor }}">
        <!-- Navigation -->
        @if (Route::has('login'))
            <nav class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white hover:text-blue-600 transition-colors">Puncak Jalil Community Hub</h2>
                        </div>
                        <div class="hidden md:flex items-center space-x-6">
                            <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Ciri-ciri</a>
                            <a href="#promotions" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Promosi</a>
                            <a href="{{ route('shops.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Kedai</a>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-300 hover:shadow-lg">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-300 hover:shadow-lg">Register</a>
                                @endif
                            @endauth
                        </div>
                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button type="button" class="text-gray-700 dark:text-gray-300 hover:text-blue-600" onclick="toggleMobileMenu()">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Mobile menu -->
                    <div id="mobile-menu" class="hidden md:hidden pb-4">
                        <div class="flex flex-col space-y-2">
                            <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 py-2">Ciri-ciri</a>
                            <a href="#promotions" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 py-2">Promosi</a>
                            <a href="{{ route('shops.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 py-2">Kedai</a>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-center">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 py-2">Log in</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-center">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
        @endif

        <!-- Main Content -->
        <main class="flex-1 flex flex-col items-center justify-center px-4 sm:px-6 py-8 lg:py-16 w-full">
            <div class="w-full max-w-5xl mx-auto text-center mb-8">
                @php
                    $siteSetting = \App\Models\SiteSetting::first();
                    $headline = $siteSetting && $siteSetting->headline ? $siteSetting->headline : 'Selamat Datang ke <span class="text-red-600">Puncak Jalil Community Hub</span>';
                    $headlineImage = $siteSetting && $siteSetting->headline_image ? asset('storage/' . $siteSetting->headline_image) : null;
                    $description = $siteSetting && $siteSetting->description ? $siteSetting->description : 'Platform komuniti perumahan: cari kedai, promosi, review, event, forum, laporan masalah & banyak lagi. Semua dalam satu tempat!';
                    $headlineSize = $siteSetting && $siteSetting->headline_font_size ? $siteSetting->headline_font_size : 'text-5xl md:text-6xl';
                    $headlineColor = $siteSetting && $siteSetting->headline_color ? $siteSetting->headline_color : 'text-gray-900 dark:text-white';
                    $headlineAlignment = $siteSetting && $siteSetting->headline_alignment ? $siteSetting->headline_alignment : 'text-center';
                @endphp
                
                @if ($headlineImage)
                    <img src="{{ $headlineImage }}" alt="Headline Image" class="mx-auto mb-6 rounded-2xl shadow-xl w-full max-w-2xl object-cover" style="max-height:320px;">
                @endif
                
                <h1 class="{{ $headlineSize }} {{ $headlineColor }} {{ $headlineAlignment }} font-extrabold mb-5 leading-tight">
                    {!! $headline !!}
                </h1>
                
                <p class="{{ $headlineAlignment }} text-xl text-gray-700 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                    {{ $description }}
                </p>
                
                <!-- Call-to-Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-tachometer-alt"></i>
                            Pergi ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}?role=vendor" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-store"></i>
                            Daftar sebagai Peniaga
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-user"></i>
                            Daftar sebagai Pengguna
                        </a>
                    @endauth
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto mb-8">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">120+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Kedai Aktif</div>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">350+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Ulasan</div>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">15</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Event Aktif</div>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-600">80+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Forum Post</div>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <form action="{{ route('shops.index') }}" method="GET" class="flex items-center max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow px-4 py-3 gap-2 mb-10">
                    <input type="text" name="search" class="flex-1 bg-transparent outline-none px-2 py-2 text-gray-700 dark:text-gray-200" placeholder="Cari kedai, servis, atau lokasi...">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Cari</button>
                </form>
            </div>
            
            <!-- Featured Promotions Section -->
            <div id="promotions" class="w-full max-w-7xl mx-auto mb-16">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">🎉 Promosi Terkini</h2>
                    <p class="text-gray-600 dark:text-gray-400">Dapatkan tawaran terbaik dari peniaga komuniti</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $featuredPromotions = \App\Models\Promotion::where('is_featured', true)
                            ->where('end_date', '>=', now())
                            ->with('shop')
                            ->take(6)
                            ->get();
                    @endphp
                    
                    @forelse($featuredPromotions as $promotion)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            @if($promotion->shop && $promotion->shop->image)
                                <img src="{{ asset('storage/' . $promotion->shop->image) }}" alt="{{ $promotion->shop->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white text-4xl">🏪</span>
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $promotion->title }}</h3>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">FEATURED</span>
                                </div>
                                
                                @if($promotion->shop)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $promotion->shop->name }}</p>
                                @endif
                                
                                <p class="text-gray-700 dark:text-gray-300 text-sm mb-4 line-clamp-2">{{ Str::limit($promotion->description, 100) }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Sampai {{ \Carbon\Carbon::parse($promotion->end_date)->format('d M Y') }}
                                    </div>
                                    <a href="{{ route('promotions.show', $promotion) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Lihat Tawaran
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-6xl mb-4">🎁</div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tiada Promosi Aktif</h3>
                            <p class="text-gray-600 dark:text-gray-400">Promosi terkini akan dipaparkan di sini</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="text-center mt-8">
                    <a href="{{ route('promotions.index') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                        Lihat Semua Promosi
                    </a>
                </div>
            </div>
            
            <div class="w-full max-w-7xl mx-auto">
                <div id="features" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-14">
                    <!-- Feature Cards -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-2 border-t-4 border-indigo-500">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-1 flex items-center gap-2">
                            <span class="material-icons text-indigo-500">storefront</span> 
                            Direktori Kedai
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">Cari, tapis & semak info kedai, servis, lokasi, waktu operasi, dan status.</div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-2 border-t-4 border-pink-500">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-1 flex items-center gap-2">
                            <span class="material-icons text-pink-500">local_offer</span> 
                            Promosi & Review
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">Promosi terkini, review pengguna, rating, dan komen untuk kedai & servis tempatan.</div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-2 border-t-4 border-yellow-400">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-1 flex items-center gap-2">
                            <span class="material-icons text-yellow-400">event</span> 
                            Event & Notis
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">Sertai event komuniti, baca notis & pengumuman penting kawasan perumahan.</div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-2 border-t-4 border-blue-500">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-1 flex items-center gap-2">
                            <span class="material-icons text-blue-500">forum</span> 
                            Forum Komuniti
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">Forum & chat ringkas untuk perbincangan, pertanyaan, dan jaringan komuniti.</div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-2 border-t-4 border-red-500">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-1 flex items-center gap-2">
                            <span class="material-icons text-red-500">report_problem</span> 
                            Laporan Masalah
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">Laporkan isu komuniti (lampu jalan, keselamatan, kebersihan) dengan mudah & pantas.</div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-2 border-t-4 border-green-500">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-1 flex items-center gap-2">
                            <span class="material-icons text-green-500">star</span> 
                            Untuk Peniaga
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">Daftar kedai, kemaskini profil, promosi khas, statistik review & klik pelanggan.</div>
                    </div>
                </div>
                
                <!-- Testimonials Section -->
                <div class="w-full max-w-7xl mx-auto mb-16">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">💬 Apa Kata Pengguna Kami</h2>
                        <p class="text-gray-600 dark:text-gray-400">Pengalaman penduduk Puncak Jalil dengan platform kami</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold">A</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Ahmad Rahman</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Penduduk Puncak Jalil</p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 italic">"Platform ini sangat membantu! Saya dapat mencari servis gunting rambut yang terbaik di kawasan dengan mudah. Review daripada pengguna lain sangat berguna."</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-green-600 dark:text-green-400 font-bold">S</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Siti Aminah</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Peniaga</p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 italic">"Sebagai peniaga, platform ini membantu saya promosi perniagaan dengan lebih efektif. Traffic pelanggan meningkat 30% sejak mendaftar!"</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-purple-600 dark:text-purple-400 font-bold">M</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Mohd Zaki</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Penduduk & Moderator Forum</p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 italic">"Forum komuniti sangat aktif! Saya dapat berkongsi maklumat penting dan mendapatkan bantuan daripada jiran-jiran. Sistem laporan juga sangat berguna."</p>
                        </div>
                    </div>
                </div>
                
                <div class="w-full flex flex-wrap justify-center gap-6 mb-14">
                    <a href="{{ route('shops.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded shadow font-semibold">Senarai Kedai</a>
                    <a href="{{ route('promotions.index') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded shadow font-semibold">Promosi</a>
                    <a href="{{ route('events.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded shadow font-semibold">Event</a>
                    <a href="{{ route('forum-posts.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded shadow font-semibold">Forum</a>
                    <a href="{{ route('reports.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded shadow font-semibold">Laporan</a>
                </div>
                
                <div class="w-full max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-14">
                    <div class="flex flex-col gap-4">
                        <div class="bg-gradient-to-r from-blue-100 to-blue-50 dark:from-gray-800 dark:to-gray-900 rounded-lg p-6 shadow">
                            <div class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Statistik Komuniti</div>
                            <div class="flex flex-wrap gap-6">
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-indigo-600">120+</span>
                                    <span class="text-xs text-gray-500">Kedai Berdaftar</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-pink-600">350+</span>
                                    <span class="text-xs text-gray-500">Review</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-yellow-500">15</span>
                                    <span class="text-xs text-gray-500">Event Aktif</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-blue-500">80+</span>
                                    <span class="text-xs text-gray-500">Forum Post</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-green-100 to-green-50 dark:from-gray-800 dark:to-gray-900 rounded-lg p-6 shadow">
                            <div class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Untuk Admin</div>
                            <div class="text-gray-600 dark:text-gray-300 text-sm">Dashboard admin untuk urus user, kedai, review, event, forum & laporan. Sistem dipantau untuk keselamatan komuniti.</div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=900&q=80" alt="Puncak Jalil" class="rounded-2xl shadow-xl w-full max-w-2xl mb-6">
                        <div class="text-center text-gray-500 dark:text-gray-400 text-xs">Gambar hiasan: Komuniti Puncak Jalil</div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Advertisement Banner -->
        <div class="w-full bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white mb-2">🚀 Tingkatkan Perniagaan Anda!</h3>
                    <p class="text-white/90 mb-6 max-w-2xl mx-auto">Dapatkan lebih ramai pelanggan dengan promosi featured dan banner iklan premium di platform komuniti Puncak Jalil</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}?role=vendor" class="bg-white text-orange-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-bold shadow-lg transition-colors">
                            Daftar sebagai Peniaga
                        </a>
                        <a href="{{ route('promotions.create') }}" class="bg-orange-600 text-white hover:bg-orange-700 px-8 py-3 rounded-lg font-bold shadow-lg transition-colors">
                            Buat Promosi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer with Contact Info -->
        @if(($siteSetting && $siteSetting->contact_phone) || ($siteSetting && $siteSetting->contact_email))
            <div class="w-full py-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-6 text-center">
                        @if($siteSetting && $siteSetting->contact_phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">{{ $siteSetting->contact_phone }}</span>
                            </div>
                        @endif
                        @if($siteSetting && $siteSetting->contact_email)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">{{ $siteSetting->contact_email }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <footer class="w-full py-4 text-center text-gray-400 text-sm bg-white/80 dark:bg-gray-900/80 border-t dark:border-gray-700">
            &copy; {{ date('Y') }} Puncak Jalil Community Hub. Dibangunkan untuk komuniti.
        </footer>
    </div>
    
    <!-- Material Icons CDN for icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.bg-white.dark\\:bg-gray-800').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
    
    <style>
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>
