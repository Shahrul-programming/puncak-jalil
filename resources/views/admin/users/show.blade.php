@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Users
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
                    User Details
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">View and manage user information</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($user->role !== 'admin' && $user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                                onclick="return confirm('Are you sure you want to impersonate this user?')">
                            <i class="fas fa-user-secret mr-2"></i>Impersonate
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Profile Photo -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        
                        <!-- Role Badge -->
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                   ($user->role === 'vendor' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : ($user->role === 'vendor' ? 'fa-store' : 'fa-user') }} mr-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                            Contact Information
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-5 mr-3"></i>
                                <span class="text-gray-900 dark:text-gray-100">{{ $user->email }}</span>
                            </div>
                            
                            @if($user->phone)
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 w-5 mr-3"></i>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $user->phone }}</span>
                                </div>
                            @endif
                            
                            @if($user->address)
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-gray-400 w-5 mr-3 mt-1"></i>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $user->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Dates -->
                    <div class="mt-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                            Account Information
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Member Since:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            @if($user->email_verified_at)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Verified:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $user->email_verified_at->format('M d, Y') }}</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full {{ $user->email_verified_at ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                                        onclick="return confirm('Are you sure you want to {{ $user->email_verified_at ? 'deactivate' : 'activate' }} this user?')">
                                    <i class="fas {{ $user->email_verified_at ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                                    {{ $user->email_verified_at ? 'Deactivate User' : 'Activate User' }}
                                </button>
                            </form>
                            
                            <!-- Delete User -->
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="fas fa-trash mr-2"></i>Delete User
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity & Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Activity Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Activity Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($user->role === 'vendor')
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <i class="fas fa-store text-blue-500 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $userStats['shops'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Shops</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <i class="fas fa-tags text-green-500 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $userStats['promotions'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Promotions</p>
                            </div>
                        @endif
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <i class="fas fa-calendar text-purple-500 text-2xl mb-2"></i>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $userStats['events'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Events</p>
                        </div>
                        <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <i class="fas fa-comments text-orange-500 text-2xl mb-2"></i>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $userStats['forum_posts'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Forum Posts</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <i class="fas fa-star text-red-500 text-2xl mb-2"></i>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $userStats['reviews'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Reviews</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($user->role === 'vendor' && $recentShops->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Shops</h3>
                        <div class="space-y-3">
                            @foreach($recentShops as $shop)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $shop->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $shop->category }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $shop->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($shop->status) }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $shop->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Forum Posts -->
            @if($recentForumPosts->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Forum Posts</h3>
                        <div class="space-y-3">
                            @foreach($recentForumPosts as $post)
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <i class="fas fa-comment text-blue-500 mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $post->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($post->content, 100) }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Reviews -->
            @if($recentReviews->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Reviews</h3>
                        <div class="space-y-3">
                            @foreach($recentReviews as $review)
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                        @endfor
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $review->shop->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($review->comment, 100) }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
