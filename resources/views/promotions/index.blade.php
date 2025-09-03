@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-tags text-green-600 mr-3"></i>
                Promosi Saya
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                @if(auth()->user()->role === 'admin')
                    Urus semua promosi dalam sistem
                @else
                    Urus promosi untuk kedai anda
                @endif
            </p>
        </div>
        
        @if(auth()->user()->shops()->where('status', 'active')->exists() || auth()->user()->role === 'admin')
            <a href="{{ route('promotions.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Promosi
            </a>
        @else
            <div class="text-center">
                <p class="text-gray-500 mb-4">Anda perlu memiliki kedai yang aktif untuk membuat promosi</p>
                <a href="{{ route('shops.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-store mr-2"></i>Daftar Kedai
                </a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Promotions Grid -->
    @if($promotions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promotions as $promotion)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <!-- Promotion Image -->
                    <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                        @if($promotion->image)
                            <img src="{{ asset('storage/' . $promotion->image) }}" 
                                 alt="{{ $promotion->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            @if($promotion->status_badge === 'active')
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Aktif
                                </span>
                            @elseif($promotion->status_badge === 'expired')
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Tamat
                                </span>
                            @elseif($promotion->status_badge === 'upcoming')
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Akan Datang
                                </span>
                            @else
                                <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>

                        <!-- Discount Badge -->
                        @if($promotion->formatted_discount)
                            <div class="absolute top-3 right-3">
                                <span class="bg-red-500 text-white px-3 py-2 rounded-full font-bold text-lg">
                                    -{{ $promotion->formatted_discount }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Promotion Content -->
                    <div class="p-6">
                        <div class="mb-2">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $promotion->title }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-store mr-1"></i>
                                {{ $promotion->shop->name }}
                            </p>
                        </div>

                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                            {{ Str::limit($promotion->description, 100) }}
                        </p>

                        <!-- Pricing -->
                        @if($promotion->original_price && $promotion->promo_price)
                            <div class="mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl font-bold text-green-600">
                                        RM{{ number_format($promotion->promo_price, 2) }}
                                    </span>
                                    <span class="text-lg text-gray-500 line-through">
                                        RM{{ number_format($promotion->original_price, 2) }}
                                    </span>
                                </div>
                                @if($promotion->savings_amount)
                                    <p class="text-sm text-green-600">
                                        Jimat RM{{ number_format($promotion->savings_amount, 2) }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <!-- Date Info -->
                        <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ $promotion->start_date->format('d M Y') }} - {{ $promotion->end_date->format('d M Y') }}
                            </div>
                            @if($promotion->isActive())
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $promotion->days_remaining }} hari lagi
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('promotions.show', $promotion) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-200"
                               title="Lihat Promosi">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </a>
                            
                            @php $user = auth()->user(); @endphp
                            @if($user && ($user->role === 'admin' || ($promotion->shop && $promotion->shop->user_id === $user->id)))
                                <a href="{{ route('promotions.edit', $promotion) }}" 
                                   class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-md transition duration-200"
                                   title="Edit Promosi">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                
                                <form action="{{ route('promotions.destroy', $promotion) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Adakah anda pasti untuk memadam promosi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md transition duration-200"
                                            title="Padam Promosi">
                                        <i class="fas fa-trash mr-1"></i> Padam
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($promotions->hasPages())
            <div class="mt-8">
                {{ $promotions->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <i class="fas fa-tags text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Tiada Promosi
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    @if(auth()->user()->role === 'admin')
                        Tiada promosi dalam sistem pada masa ini.
                    @else
                        Anda belum membuat sebarang promosi.
                    @endif
                </p>
                
                @if(auth()->user()->shops()->where('status', 'active')->exists() || auth()->user()->role === 'admin')
                    <a href="{{ route('promotions.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Buat Promosi Pertama
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
