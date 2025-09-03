@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('reports.index') }}" 
                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-plus-circle text-red-600 mr-2"></i>
                    Buat Laporan Baru
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Laporkan isu atau masalah dalam komuniti untuk tindakan yang sewajarnya</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Category Selection -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tag mr-1"></i>Kategori Laporan <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 @error('category') border-red-300 @enderror">
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
                    <div class="mt-3 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <h4 class="text-sm font-medium text-red-900 dark:text-red-100 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>Panduan Kategori:
                        </h4>
                        <div class="text-xs text-red-800 dark:text-red-200 space-y-1">
                            <div><strong>Infrastruktur:</strong> Jalan rosak, lubang, kerb rosak</div>
                            <div><strong>Keselamatan:</strong> Kawasan gelap, aktiviti mencurigakan</div>
                            <div><strong>Lampu Jalan:</strong> Lampu rosak atau mati</div>
                            <div><strong>Kebersihan:</strong> Sampah terbiar, kawasan kotor</div>
                            <div><strong>Longkang & Parit:</strong> Tersumbat, bau busuk</div>
                            <div><strong>Kemudahan Awam:</strong> Taman permainan, tempat letak kereta</div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-edit mr-1"></i>Penerangan Masalah <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="6"
                              placeholder="Jelaskan masalah dengan terperinci. Sertakan masa kejadian jika perlu..."
                              required
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Minimum 10 aksara. Semakin terperinci, semakin mudah untuk diselesaikan.
                    </p>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>Lokasi
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}"
                           placeholder="Contoh: Jalan Puncak 2, depan rumah No. 45"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 @error('location') border-red-300 @enderror">
                    @error('location')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Sertakan lokasi yang tepat untuk memudahkan tindakan
                    </p>
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-camera mr-1"></i>Gambar (Pilihan)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                    <span>Upload gambar</span>
                                    <input id="image" name="image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" class="h-32 w-auto rounded-lg shadow-sm" alt="Preview">
                        <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                            <i class="fas fa-times mr-1"></i>Buang gambar
                        </button>
                    </div>
                </div>

                <!-- Reporting Guidelines -->
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Garis Panduan Laporan
                            </h4>
                            <div class="mt-2 text-xs text-yellow-700 dark:text-yellow-300">
                                <ul class="space-y-1">
                                    <li>• Pastikan maklumat yang diberikan adalah tepat dan benar</li>
                                    <li>• Gunakan bahasa yang sopan dan professional</li>
                                    <li>• Sertakan gambar jika ada untuk bukti</li>
                                    <li>• Untuk kecemasan, hubungi 999 atau authorities yang berkaitan</li>
                                    <li>• Laporan palsu boleh dikenakan tindakan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Notice -->
                <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-red-600 dark:text-red-400 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Kecemasan
                            </h4>
                            <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                                Untuk situasi kecemasan, sila hubungi 999 (Polis/Bomba/Hospital) atau authorities yang berkaitan secara terus. 
                                Sistem laporan ini untuk isu-isu komuniti yang tidak kritikal.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('reports.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-8 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>Hantar Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const input = document.getElementById('image');
    const previewContainer = document.getElementById('imagePreview');
    
    input.value = '';
    previewContainer.classList.add('hidden');
}
</script>
@endpush
@endsection
