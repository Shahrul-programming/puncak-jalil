@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('forum-posts.show', $forumPost) }}" 
                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    Edit Post
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Kemaskini maklumat post anda</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('forum-posts.update', $forumPost) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Locked Notice -->
                @if($forumPost->is_locked && Auth::user()->role !== 'admin')
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-red-600 dark:text-red-400 mr-2"></i>
                            <p class="text-red-800 dark:text-red-200 font-medium">Post ini telah dikunci dan tidak boleh diedit.</p>
                        </div>
                    </div>
                @endif

                <!-- Category Selection -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tag mr-1"></i>Kategori
                    </label>
                    <select id="category" 
                            name="category" 
                            required
                            {{ ($forumPost->is_locked && Auth::user()->role !== 'admin') ? 'disabled' : '' }}
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('category') border-red-300 @enderror">
                        @foreach($categories as $key => $name)
                            <option value="{{ $key }}" {{ (old('category', $forumPost->category) == $key) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-heading mr-1"></i>Tajuk Post
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $forumPost->title) }}"
                           placeholder="Masukkan tajuk yang menarik dan jelas..."
                           maxlength="255"
                           required
                           {{ ($forumPost->is_locked && Auth::user()->role !== 'admin') ? 'readonly' : '' }}
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <span id="titleCount">{{ strlen($forumPost->title) }}</span>/255 aksara
                    </p>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-edit mr-1"></i>Kandungan
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="12"
                              placeholder="Tulis kandungan post anda di sini..."
                              required
                              {{ ($forumPost->is_locked && Auth::user()->role !== 'admin') ? 'readonly' : '' }}
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-300 @enderror">{{ old('content', $forumPost->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Post Info -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Maklumat Post:
                    </h4>
                    <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <div>• Dicipta: {{ $forumPost->formatted_date }}</div>
                        <div>• Views: {{ $forumPost->views_count }}</div>
                        <div>• Balasan: {{ $forumPost->replies->count() }}</div>
                        @if($forumPost->is_pinned)
                            <div class="text-yellow-600 dark:text-yellow-400">• Status: Disematkan</div>
                        @endif
                        @if($forumPost->is_locked)
                            <div class="text-red-600 dark:text-red-400">• Status: Dikunci</div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('forum-posts.show', $forumPost) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    @if(!$forumPost->is_locked || Auth::user()->role === 'admin')
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>Kemaskini Post
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const titleCount = document.getElementById('titleCount');
    
    function updateTitleCount() {
        const length = titleInput.value.length;
        titleCount.textContent = length;
        titleCount.className = length > 240 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400';
    }
    
    if (titleInput && !titleInput.readOnly) {
        titleInput.addEventListener('input', updateTitleCount);
    }
});
</script>
@endpush
@endsection
