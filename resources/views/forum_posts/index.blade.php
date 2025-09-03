@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-comments text-blue-600 mr-2"></i>
                    Forum Komuniti
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Ruang perbincangan untuk ahli komuniti Puncak Jalil</p>
            </div>
            <a href="{{ route('forum-posts.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Cipta Post Baru
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <i class="fas fa-comments text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Posts</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalPosts }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <i class="fas fa-reply text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Balasan</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalReplies }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ahli Aktif</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <form method="GET" action="{{ route('forum-posts.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1"></i>Cari
                </label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ $search }}"
                       placeholder="Cari dalam tajuk atau kandungan..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="sm:w-48">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-tag mr-1"></i>Kategori
                </label>
                <select id="category" 
                        name="category"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $name)
                        <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if($search || $category)
                    <a href="{{ route('forum-posts.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Forum Posts List -->
    <div class="space-y-4">
        @forelse($forumPosts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Post Header -->
                            <div class="flex items-center space-x-3 mb-3">
                                @if($post->is_pinned)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                        <i class="fas fa-thumbtack mr-1"></i>Disematkan
                                    </span>
                                @endif
                                @if($post->is_locked)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                        <i class="fas fa-lock mr-1"></i>Dikunci
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                    <i class="fas fa-tag mr-1"></i>{{ $post->category_name }}
                                </span>
                            </div>

                            <!-- Post Title & Content Preview -->
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <a href="{{ route('forum-posts.show', $post) }}" 
                                   class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>

                            <!-- Post Meta -->
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-6">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    <span class="font-medium">{{ $post->user->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $post->time_ago }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    <span>{{ $post->views_count }} views</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-comments mr-1"></i>
                                    <span>{{ $post->replies->count() }} balasan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2 ml-4">
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <div class="flex space-x-1">
                                        <form method="POST" action="{{ route('forum-posts.pin', $post) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-700 p-2 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors duration-200"
                                                    title="{{ $post->is_pinned ? 'Buka sematan' : 'Sematkan' }}">
                                                <i class="fas fa-thumbtack"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('forum-posts.lock', $post) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                                                    title="{{ $post->is_locked ? 'Buka kunci' : 'Kunci' }}">
                                                <i class="fas fa-{{ $post->is_locked ? 'unlock' : 'lock' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                @if(Auth::user()->role === 'admin' || Auth::id() === $post->user_id)
                                    <a href="{{ route('forum-posts.edit', $post) }}" 
                                       class="text-blue-600 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('forum-posts.destroy', $post) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Adakah anda pasti untuk memadam post ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                                                title="Padam">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-comments text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Tiada post forum ditemui</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    @if($search || $category)
                        Tiada post yang sepadan dengan kriteria carian anda.
                    @else
                        Belum ada post forum. Jadilah yang pertama untuk memulakan perbincangan!
                    @endif
                </p>
                @if($search || $category)
                    <a href="{{ route('forum-posts.index') }}" 
                       class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200 mr-3">
                        <i class="fas fa-times mr-2"></i>Reset Carian
                    </a>
                @endif
                <a href="{{ route('forum-posts.create') }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Cipta Post Pertama
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($forumPosts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $forumPosts->withQueryString()->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection
