@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('forum-posts.show', $forumReply->post) }}" 
                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-reply text-blue-600 mr-2"></i>
                    Edit Balasan
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Kemaskini balasan untuk: {{ $forumReply->post->title }}</p>
        </div>

        <!-- Original Post Context -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Post Asal:</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                {{ Str::limit($forumReply->post->content, 200) }}
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('forum-replies.update', $forumReply) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Locked Notice -->
                @if($forumReply->post->is_locked && Auth::user()->role !== 'admin')
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-red-600 dark:text-red-400 mr-2"></i>
                            <p class="text-red-800 dark:text-red-200 font-medium">Post ini telah dikunci dan balasan tidak boleh diedit.</p>
                        </div>
                    </div>
                @endif

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-edit mr-1"></i>Kandungan Balasan
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="8"
                              placeholder="Tulis balasan anda di sini... (minimum 10 aksara)"
                              required
                              {{ ($forumReply->post->is_locked && Auth::user()->role !== 'admin') ? 'readonly' : '' }}
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-300 @enderror">{{ old('content', $forumReply->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Minimum 10 aksara diperlukan
                    </p>
                </div>

                <!-- Reply Info -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Maklumat Balasan:
                    </h4>
                    <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <div>• Dicipta: {{ $forumReply->created_at->format('d M Y, H:i') }}</div>
                        <div>• Dikemaskini: {{ $forumReply->updated_at->format('d M Y, H:i') }}</div>
                        <div>• Dalam post: {{ $forumReply->post->title }}</div>
                    </div>
                </div>

                <!-- Guidelines -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                        <i class="fas fa-lightbulb mr-1"></i>Tips Balasan yang Baik:
                    </h4>
                    <div class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                        <div>• Gunakan bahasa yang sopan dan hormat</div>
                        <div>• Berikan jawapan yang membantu dan relevan</div>
                        <div>• Elakkan spam atau balasan berulang</div>
                        <div>• Jika tidak bersetuju, nyatakan dengan cara yang baik</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('forum-posts.show', $forumReply->post) }}#reply-{{ $forumReply->id }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    @if(!$forumReply->post->is_locked || Auth::user()->role === 'admin')
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>Kemaskini Balasan
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection
