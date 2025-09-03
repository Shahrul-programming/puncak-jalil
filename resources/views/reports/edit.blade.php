@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('reports.show', $report) }}" 
                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-edit text-red-600 mr-2"></i>
                    Edit Laporan #{{ $report->id }}
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Kemaskini maklumat laporan anda</p>
        </div>

        <!-- Current Status Warning -->
        @if($report->status === 'selesai')
            <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Laporan Telah Selesai
                        </h4>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">
                            Laporan ini telah ditandai sebagai selesai. Pertimbangkan untuk membuat laporan baru jika masalah berlanjutan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

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
                            <option value="{{ $key }}" {{ (old('category', $report->category) == $key) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-edit mr-1"></i>Penerangan Masalah <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="6"
                              placeholder="Jelaskan masalah dengan terperinci..."
                              required
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 @error('description') border-red-300 @enderror">{{ old('description', $report->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>Lokasi
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $report->location) }}"
                           placeholder="Contoh: Jalan Puncak 2, depan rumah No. 45"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 @error('location') border-red-300 @enderror">
                    @error('location')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($report->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-image mr-1"></i>Gambar Semasa
                        </label>
                        <div class="flex items-start space-x-4">
                            <img src="{{ Storage::url($report->image) }}" 
                                 alt="Current image" 
                                 class="h-24 w-auto rounded-lg shadow-sm">
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    Gambar semasa akan dikekalkan jika tiada gambar baru dimuat naik.
                                </p>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="remove_image" 
                                           value="1"
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Buang gambar semasa</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- New Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-camera mr-1"></i>{{ $report->image ? 'Gambar Baru (Pilihan)' : 'Gambar (Pilihan)' }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                    <span>Upload gambar baru</span>
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
                    
                    <!-- New Image Preview -->
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" class="h-32 w-auto rounded-lg shadow-sm" alt="Preview">
                        <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                            <i class="fas fa-times mr-1"></i>Buang gambar baru
                        </button>
                    </div>
                </div>

                <!-- Authorization Check -->
                @if(auth()->user()->is_admin)
                    <!-- Admin can edit resolved reports -->
                @elseif($report->status === 'selesai')
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-red-600 dark:text-red-400 mr-3"></i>
                            <div>
                                <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Laporan Terkunci
                                </h4>
                                <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                                    Laporan yang telah selesai tidak boleh diedit kecuali oleh admin.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Edit Guidelines -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                Panduan Edit
                            </h4>
                            <div class="mt-2 text-xs text-blue-700 dark:text-blue-300">
                                <ul class="space-y-1">
                                    <li>• Pastikan maklumat yang dikemaskini adalah tepat</li>
                                    <li>• Admin akan menerima notifikasi tentang perubahan</li>
                                    <li>• Status laporan mungkin dikembalikan jika perubahan signifikan</li>
                                    <li>• Gambar lama akan digantikan jika gambar baru dimuat naik</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('reports.show', $report) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    
                    @if(auth()->user()->is_admin || $report->status !== 'selesai')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-8 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>Kemaskini Laporan
                        </button>
                    @else
                        <button type="button" 
                                disabled
                                class="bg-gray-400 text-gray-600 px-8 py-2 rounded-lg font-medium cursor-not-allowed flex items-center">
                            <i class="fas fa-lock mr-2"></i>Tidak Boleh Edit
                        </button>
                    @endif
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
