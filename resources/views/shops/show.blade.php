@extends('layouts.app')

@section('head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Shop Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Shop Image -->
            <div class="flex-shrink-0">
                @if($shop->image)
                    <img src="{{ asset('storage/' . $shop->image) }}" 
                         alt="{{ $shop->name }}" 
                         class="w-full md:w-64 h-48 object-cover rounded-lg">
                @else
                    <div class="w-full md:w-64 h-48 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500 dark:text-gray-400">No Image</span>
                    </div>
                @endif
            </div>
            
            <!-- Shop Info -->
            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $shop->name }}
                        </h1>
                        <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium mb-2">
                            {{ ucfirst($shop->category) }}
                        </span>
                    </div>
                    
                    <!-- Rating Summary -->
                    <div class="text-right">
                        <div class="flex items-center justify-end mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageRating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @elseif($i - 0.5 <= $averageRating)
                                    <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half-fill">
                                                <stop offset="50%" stop-color="currentColor"/>
                                                <stop offset="50%" stop-color="transparent"/>
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half-fill)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20">
                                        <path fill="currentColor" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ number_format($averageRating, 1) }} / 5 ({{ $reviewCount }} reviews)
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    {{ $shop->description }}
                </p>
                
                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($shop->address)
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $shop->address }}</span>
                        </div>
                    @endif
                    
                    @if($shop->phone)
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            <a href="tel:{{ $shop->phone }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">{{ $shop->phone }}</a>
                        </div>
                    @endif
                    
                    @if($shop->formatted_whatsapp_link)
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            <a href="{{ $shop->formatted_whatsapp_link }}" target="_blank" class="text-sm text-green-600 dark:text-green-400 hover:underline">WhatsApp</a>
                        </div>
                    @endif
                    
                    @if($shop->website)
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.148.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                            </svg>
                            <a href="{{ $shop->website }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Website</a>
                        </div>
                    @endif
                    
                    @if($shop->opening_hours)
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $shop->opening_hours }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Location Map -->
    @if($shop->latitude && $shop->longitude)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Location</h2>
            <div class="rounded-lg overflow-hidden mb-4" style="height: 400px;">
                <div id="shop-map" class="w-full h-full"></div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="https://www.google.com/maps?q={{ $shop->latitude }},{{ $shop->longitude }}" 
                   target="_blank"
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Open in Google Maps
                </a>
                <a href="https://waze.com/ul?q={{ $shop->latitude }},{{ $shop->longitude }}" 
                   target="_blank"
                   class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    Open in Waze
                </a>
                <button id="get-directions" 
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Get Directions
                </button>
            </div>
        </div>
    @endif

    <!-- Promotions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Current Promotions</h2>
        @php
            $activePromotions = $shop->promotions->filter(function($p) {
                return \Carbon\Carbon::parse($p->end_date)->gte(now());
            });
        @endphp

        @if($activePromotions->count() > 0)
            <div class="space-y-4">
                @foreach($activePromotions as $promo)
                    <div class="border border-green-200 dark:border-green-700 bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-green-800 dark:text-green-200 mb-1">
                                    {{ $promo->title }}
                                </h3>
                                <p class="text-green-700 dark:text-green-300 text-sm mb-2">
                                    {{ $promo->description }}
                                </p>
                                @if($promo->discount_percentage)
                                    <span class="inline-block bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                        {{ $promo->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-green-600 dark:text-green-400 font-medium">
                                    Valid until
                                </div>
                                <div class="text-sm text-green-800 dark:text-green-200 font-semibold">
                                    {{ \Carbon\Carbon::parse($promo->end_date)->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No active promotions at the moment.</p>
                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Check back later for special offers!</p>
            </div>
        @endif
    </div>

    <!-- Reviews Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Customer Reviews</h2>
        
        <!-- Rating Overview -->
        <div class="flex flex-col md:flex-row gap-8 mb-8">
            <!-- Overall Rating -->
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    {{ number_format($averageRating, 1) }}
                </div>
                <div class="flex justify-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $averageRating)
                            <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @elseif($i - 0.5 <= $averageRating)
                            <svg class="w-6 h-6 text-yellow-400" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="half-fill">
                                        <stop offset="50%" stop-color="currentColor"/>
                                        <stop offset="50%" stop-color="transparent"/>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half-fill)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20">
                                <path fill="currentColor" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Based on {{ $reviewCount }} reviews
                </div>
            </div>
            
            <!-- Rating Distribution -->
            @if($reviewCount > 0)
                <div class="flex-1">
                    @php $distribution = $shop->rating_distribution; @endphp
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400 w-8">{{ $i }}</span>
                            <svg class="w-4 h-4 text-yellow-400 fill-current mr-2" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <div class="flex-1 mx-2">
                                <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" 
                                         style="width: {{ $distribution[$i]['percentage'] }}%"></div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400 w-12">{{ $distribution[$i]['count'] }}</span>
                        </div>
                    @endfor
                </div>
            @endif
        </div>
        
        <!-- Add Review Form -->
        @auth
            @if(!$shop->hasUserReviewed(auth()->id()))
                <div class="border-t dark:border-gray-700 pt-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Write a Review</h3>
                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        
                        <!-- Rating Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Rating
                            </label>
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star w-8 h-8 text-gray-300 hover:text-yellow-400 focus:outline-none"
                                            data-rating="{{ $i }}">
                                        <svg class="w-full h-full fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" required>
                        </div>
                        
                        <!-- Comment Input -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Comment
                            </label>
                            <textarea name="comment" id="comment" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                      placeholder="Share your experience..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Submit Review
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t dark:border-gray-700 pt-6 mb-6">
                    <p class="text-gray-600 dark:text-gray-400">You have already reviewed this shop.</p>
                </div>
            @endif
        @else
            <div class="border-t dark:border-gray-700 pt-6 mb-6">
                <p class="text-gray-600 dark:text-gray-400">
                    <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Login</a> 
                    to write a review.
                </p>
            </div>
        @endauth

        <!-- Reviews List -->
        @if($shop->reviews->count() > 0)
            <div class="border-t dark:border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">All Reviews</h3>
                <div class="space-y-6">
                    @foreach($shop->reviews->sortByDesc('created_at') as $review)
                        <div class="border-b dark:border-gray-700 pb-6 last:border-b-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $review->user->name }}</h4>
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20">
                                                    <path fill="currentColor" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $review->rating }}/5
                                        </span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $review->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="border-t dark:border-gray-700 pt-6">
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No reviews yet. Be the first to review!</p>
            </div>
        @endif
    </div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <!-- JavaScript for Star Rating -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Star Rating functionality
            const stars = document.querySelectorAll('.rating-star');
            const ratingInput = document.getElementById('rating-input');
            let selectedRating = 0;

            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    selectedRating = index + 1;
                    ratingInput.value = selectedRating;
                    updateStars();
                });

                star.addEventListener('mouseenter', function() {
                    highlightStars(index + 1);
                });
            });

            document.querySelector('form').addEventListener('mouseleave', function() {
                updateStars();
            });

            function updateStars() {
                stars.forEach((star, index) => {
                    if (index < selectedRating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }

            // Interactive Map functionality
            @if($shop->latitude && $shop->longitude)
                const lat = {{ $shop->latitude }};
                const lng = {{ $shop->longitude }};
                const shopName = @json($shop->name);
                const shopAddress = @json($shop->address);

                // Initialize the map
                const map = L.map('shop-map').setView([lat, lng], 15);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Custom icon for shop marker
                const shopIcon = L.divIcon({
                    className: 'custom-shop-marker',
                    html: `
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg border-2 border-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    `,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                // Add marker for the shop
                const marker = L.marker([lat, lng], { icon: shopIcon }).addTo(map);

                // Create popup content
                const popupContent = `
                    <div class="p-2">
                        <h3 class="font-semibold text-lg text-gray-900 mb-1">${shopName}</h3>
                        <p class="text-sm text-gray-600 mb-2">${shopAddress}</p>
                        <div class="flex space-x-2">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
                               target="_blank" 
                               class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                Google Maps
                            </a>
                            <a href="https://waze.com/ul?q=${lat},${lng}" 
                               target="_blank" 
                               class="text-xs bg-cyan-600 text-white px-2 py-1 rounded hover:bg-cyan-700">
                                Waze
                            </a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);

                // Get Directions button functionality
                document.getElementById('get-directions').addEventListener('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            
                            // Add user location marker
                            const userIcon = L.divIcon({
                                className: 'custom-user-marker',
                                html: `
                                    <div class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg border-2 border-white">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                `,
                                iconSize: [24, 24],
                                iconAnchor: [12, 24]
                            });

                            const userMarker = L.marker([userLat, userLng], { icon: userIcon }).addTo(map);
                            userMarker.bindPopup('<div class="p-1 text-sm"><strong>Your Location</strong></div>');

                            // Fit map to show both markers
                            const group = new L.featureGroup([marker, userMarker]);
                            map.fitBounds(group.getBounds().pad(0.1));

                            // Calculate and display distance
                            const distance = map.distance([userLat, userLng], [lat, lng]);
                            const distanceKm = (distance / 1000).toFixed(2);
                            
                            userMarker.bindPopup(`
                                <div class="p-2 text-sm">
                                    <strong>Your Location</strong><br>
                                    Distance to ${shopName}: ${distanceKm} km
                                </div>
                            `);
                        }, function(error) {
                            alert('Unable to get your location. Please allow location access.');
                        });
                    } else {
                        alert('Geolocation is not supported by this browser.');
                    }
                });
            @endif
        });
    </script>
</div>
@endsection
