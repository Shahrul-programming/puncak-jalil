@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('forum-posts.index') }}" 
                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                    Cipta Post Baru
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Kongsi idea, soalan, atau maklumat dengan komuniti</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('forum-posts.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Category Selection -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tag mr-1"></i>Kategori
                    </label>
                    <select id="category" 
                            name="category" 
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('category') border-red-300 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $key => $name)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    
                    <!-- Category Guide -->
                    <div class="mt-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>Panduan Kategori:
                        </h4>
                        <div class="text-xs text-blue-800 dark:text-blue-200 space-y-1">
                            <div><strong>Perbincangan Umum:</strong> Topik umum, perkenalan, chat santai</div>
                            <div><strong>Pengumuman:</strong> Berita penting, notis rasmi</div>
                            <div><strong>Soalan & Jawapan:</strong> Tanya soalan, minta nasihat</div>
                            <div><strong>Cadangan:</strong> Idea penambahbaikan komuniti</div>
                            <div><strong>Jual Beli:</strong> Iklan jual beli barang dalam komuniti</div>
                            <div><strong>Keselamatan:</strong> Isu keselamatan, laporan suspek</div>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-heading mr-1"></i>Tajuk Post
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="Masukkan tajuk yang menarik dan jelas..."
                           maxlength="255"
                           required
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <span id="titleCount">0</span>/255 aksara
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
                              placeholder="Tulis kandungan post anda di sini. Sila jelas dan sopan dalam penyampaian..."
                              required
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    
                    <!-- Writing Tips -->
                    <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>Tips Menulis Post yang Baik:
                        </h4>
                        <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                            <div>• Gunakan bahasa yang sopan dan mudah difahami</div>
                            <div>• Sertakan maklumat yang relevan dan tepat</div>
                            <div>• Untuk jual beli, sertakan harga dan gambar jika perlu</div>
                            <div>• Untuk aduan, berikan maklumat lokasi yang tepat</div>
                            <div>• Elakkan spam dan post berulang</div>
                        </div>
                    </div>
                </div>

                <!-- Community Guidelines -->
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Garis Panduan Komuniti
                            </h4>
                            <div class="mt-2 text-xs text-yellow-700 dark:text-yellow-300">
                                <p>Dengan menyiarkan post ini, anda bersetuju untuk:</p>
                                <ul class="mt-1 space-y-1">
                                    <li>• Menghormati ahli komuniti lain</li>
                                    <li>• Tidak menyiarkan kandungan yang menyinggung atau tidak sesuai</li>
                                    <li>• Tidak spam atau iklan berlebihan</li>
                                    <li>• Mematuhi undang-undang tempatan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('forum-posts.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>Siar Post
                    </button>
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
    
    titleInput.addEventListener('input', updateTitleCount);
    updateTitleCount();
});
</script>
@endpush
@endsection
