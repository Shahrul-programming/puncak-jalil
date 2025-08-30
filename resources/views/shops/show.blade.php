@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('shops.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Direktori
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Shop Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shop Header -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <!-- Shop Image Placeholder -->
                    <div class="h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $shop->name }}</h1>
                                <p class="text-lg text-blue-600 dark:text-blue-400 font-medium">{{ $shop->category }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($shop->status === 'active') bg-green-100 text-green-800
                                @elseif($shop->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($shop->status) }}
                            </span>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400 mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageRating))
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                                {{ number_format($averageRating, 1) }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">
                                ({{ $reviewCount }} {{ $reviewCount == 1 ? 'ulasan' : 'ulasan' }})
                            </span>
                        </div>

                        <!-- Description -->
                        @if($shop->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Tentang Kami</h3>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $shop->description }}</p>
                            </div>
                        @endif

                        <!-- Contact Actions -->
                        <div class="flex flex-wrap gap-3">
                            @if($shop->phone)
                                <a href="tel:{{ $shop->phone }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    Hubungi: {{ $shop->phone }}
                                </a>
                            @endif

                            @if($shop->formatted_whatsapp_link)
                                <a href="{{ $shop->formatted_whatsapp_link }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.309"/>
                                    </svg>
                                    WhatsApp
                                </a>
                            @endif

                            @auth
                                @if(auth()->user()->id === $shop->user_id || auth()->user()->role === 'admin')
                                    <a href="{{ route('shops.edit', $shop) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Kedai
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            Ulasan Pelanggan ({{ $reviewCount }})
                        </h2>
                        @auth
                            <a href="{{ route('reviews.create', ['shop_id' => $shop->id]) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                Tulis Ulasan
                            </a>
                        @endauth
                    </div>

                    @if($shop->reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($shop->reviews->take(5) as $review)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-200">{{ $review->user->name }}</h4>
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-600 dark:text-gray-400">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($shop->reviews->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('reviews.index', ['shop_id' => $shop->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Lihat semua ulasan
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Belum ada ulasan</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jadilah orang pertama yang memberikan ulasan!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Shop Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Maklumat Kedai</h3>
                    
                    <div class="space-y-3">
                        <!-- Address -->
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Alamat</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $shop->address }}</p>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        @if($shop->opening_hours)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Waktu Operasi</p>
                                    <p class="text-gray-800 dark:text-gray-200">{{ $shop->opening_hours }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Owner -->
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pemilik</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $shop->user->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Promotions -->
                @if($shop->promotions->where('end_date', '>=', now())->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Promosi Terkini</h3>
                        
                        <div class="space-y-3">
                            @foreach($shop->promotions->where('end_date', '>=', now())->take(3) as $promotion)
                                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-lg">
                                    <h4 class="font-medium mb-1">{{ $promotion->title }}</h4>
                                    <p class="text-sm opacity-90">{{ Str::limit($promotion->description, 60) }}</p>
                                    <p class="text-xs mt-2 opacity-80">
                                        Hingga: {{ $promotion->end_date->format('d M Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
