@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-6">
            <a href="{{ route('forum-posts.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                <i class="fas fa-comments mr-1"></i>Forum
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-gray-100">{{ $forumPost->category_name }}</span>
        </div>

        <!-- Main Post -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <!-- Post Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($forumPost->is_pinned)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                <i class="fas fa-thumbtack mr-1"></i>Disematkan
                            </span>
                        @endif
                        @if($forumPost->is_locked)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                <i class="fas fa-lock mr-1"></i>Dikunci
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                            <i class="fas fa-tag mr-1"></i>{{ $forumPost->category_name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            <span>{{ $forumPost->views_count }} views</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-comments mr-1"></i>
                            <span>{{ $forumPost->replies->count() }} balasan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Content -->
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $forumPost->title }}
                </h1>
                
                <div class="prose dark:prose-dark max-w-none mb-6">
                    {!! nl2br(e($forumPost->content)) !!}
                </div>

                <!-- Post Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                {{ substr($forumPost->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $forumPost->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $forumPost->formatted_date }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @auth
                        <div class="flex items-center space-x-2">
                            @if(Auth::user()->role === 'admin')
                                <form method="POST" action="{{ route('forum-posts.pin', $forumPost) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-yellow-600 hover:text-yellow-700 px-3 py-1 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors duration-200 text-sm"
                                            title="{{ $forumPost->is_pinned ? 'Buka sematan' : 'Sematkan' }}">
                                        <i class="fas fa-thumbtack mr-1"></i>
                                        {{ $forumPost->is_pinned ? 'Buka Sematkan' : 'Sematkan' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('forum-posts.lock', $forumPost) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 px-3 py-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 text-sm"
                                            title="{{ $forumPost->is_locked ? 'Buka kunci' : 'Kunci' }}">
                                        <i class="fas fa-{{ $forumPost->is_locked ? 'unlock' : 'lock' }} mr-1"></i>
                                        {{ $forumPost->is_locked ? 'Buka Kunci' : 'Kunci' }}
                                    </button>
                                </form>
                            @endif
                            @if(Auth::user()->role === 'admin' || Auth::id() === $forumPost->user_id)
                                <a href="{{ route('forum-posts.edit', $forumPost) }}" 
                                   class="text-blue-600 hover:text-blue-700 px-3 py-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form method="POST" action="{{ route('forum-posts.destroy', $forumPost) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Adakah anda pasti untuk memadam post ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 px-3 py-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Padam
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Replies Section -->
        <div id="replies" class="space-y-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-comments mr-2 text-blue-600"></i>
                    Balasan ({{ $forumPost->replies->count() }})
                </h3>
            </div>

            @forelse($forumPost->replies as $reply)
                <div id="reply-{{ $reply->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    {{ substr($reply->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $reply->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="prose dark:prose-dark max-w-none">
                                        {!! nl2br(e($reply->content)) !!}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reply Action Buttons -->
                            @auth
                                @if(Auth::user()->role === 'admin' || Auth::id() === $reply->user_id)
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('forum-replies.edit', $reply) }}" 
                                           class="text-blue-600 hover:text-blue-700 p-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form method="POST" action="{{ route('forum-replies.destroy', $reply) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('Adakah anda pasti untuk memadam balasan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-700 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                                                    title="Padam">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <i class="fas fa-comments text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Belum ada balasan</h4>
                    <p class="text-gray-600 dark:text-gray-400">Jadilah yang pertama untuk membalas post ini!</p>
                </div>
            @endforelse
        </div>

        <!-- Reply Form -->
        @auth
            @if(!$forumPost->is_locked)
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-reply mr-2 text-blue-600"></i>Tulis Balasan
                        </h4>
                        <form method="POST" action="{{ route('forum-replies.store', $forumPost) }}">
                            @csrf
                            <div class="mb-4">
                                <textarea name="content" 
                                          rows="4" 
                                          placeholder="Tulis balasan anda di sini... (minimum 10 aksara)"
                                          required
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Sila jaga kesopanan dalam balasan anda
                                </p>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i>Hantar Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="mt-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 text-center">
                    <i class="fas fa-lock text-red-600 dark:text-red-400 text-2xl mb-2"></i>
                    <h4 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Post Dikunci</h4>
                    <p class="text-red-700 dark:text-red-300">Post ini telah dikunci oleh admin. Balasan baru tidak dibenarkan.</p>
                </div>
            @endif
        @else
            <div class="mt-8 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6 text-center">
                <i class="fas fa-sign-in-alt text-gray-400 text-2xl mb-2"></i>
                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Login Diperlukan</h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Sila login untuk membalas post ini.</p>
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            </div>
        @endauth

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <div class="mt-12">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                    <i class="fas fa-lightbulb mr-2 text-yellow-600"></i>
                    Post Berkaitan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($relatedPosts as $related)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow duration-200">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                <a href="{{ route('forum-posts.show', $related) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                    {{ $related->title }}
                                </a>
                            </h4>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $related->user->name }}</span>
                                <span>{{ $related->time_ago }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.prose {
    color: inherit;
}
.prose dark:prose-dark {
    color: inherit;
}
</style>
@endpush
@endsection
