@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 mb-2">
                <a href="{{ route('promotions.index') }}" 
                   class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Promosi
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                Tambah Promosi Baru
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Buat promosi menarik untuk menarik pelanggan</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form method="POST" action="{{ route('promotions.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Shop Selection -->
                <div>
                    <label for="shop_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Kedai <span class="text-red-500">*</span>
                    </label>
                    <select name="shop_id" id="shop_id" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('shop_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kedai --</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('shop_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tajuk Promosi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror"
                           placeholder="Contoh: Diskaun 50% untuk semua menu">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror"
                              placeholder="Terangkan promosi anda dengan lebih terperinci...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tarikh Mula <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tarikh Tamat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Maklumat Harga (Pilihan)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Peratus Diskaun (%)
                            </label>
                            <input type="number" name="discount_percentage" id="discount_percentage" 
                                   value="{{ old('discount_percentage') }}" min="0" max="100" step="0.01"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('discount_percentage') border-red-500 @enderror"
                                   placeholder="Contoh: 20">
                            @error('discount_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga Asal (RM)
                            </label>
                            <input type="number" name="original_price" id="original_price" 
                                   value="{{ old('original_price') }}" min="0" step="0.01"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('original_price') border-red-500 @enderror"
                                   placeholder="Contoh: 50.00">
                            @error('original_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="promo_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga Promosi (RM)
                            </label>
                            <input type="number" name="promo_price" id="promo_price" 
                                   value="{{ old('promo_price') }}" min="0" step="0.01"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('promo_price') border-red-500 @enderror"
                                   placeholder="Contoh: 40.00">
                            @error('promo_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Gambar Promosi
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('image') border-red-500 @enderror">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Format yang disokong: JPG, PNG, GIF. Maksimum 2MB.
                    </p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('promotions.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Promosi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-calculate promo price when discount percentage is entered
    document.getElementById('discount_percentage').addEventListener('input', function() {
        const originalPrice = parseFloat(document.getElementById('original_price').value) || 0;
        const discountPercentage = parseFloat(this.value) || 0;
        
        if (originalPrice > 0 && discountPercentage > 0) {
            const promoPrice = originalPrice - (originalPrice * discountPercentage / 100);
            document.getElementById('promo_price').value = promoPrice.toFixed(2);
        }
    });

    // Auto-calculate discount percentage when prices are entered
    document.getElementById('original_price').addEventListener('input', calculateDiscount);
    document.getElementById('promo_price').addEventListener('input', calculateDiscount);

    function calculateDiscount() {
        const originalPrice = parseFloat(document.getElementById('original_price').value) || 0;
        const promoPrice = parseFloat(document.getElementById('promo_price').value) || 0;
        
        if (originalPrice > 0 && promoPrice > 0 && promoPrice < originalPrice) {
            const discountPercentage = ((originalPrice - promoPrice) / originalPrice) * 100;
            document.getElementById('discount_percentage').value = discountPercentage.toFixed(2);
        }
    }
</script>
@endsection
