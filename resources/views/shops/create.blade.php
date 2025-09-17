@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tambah Kedai Baru</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftarkan perniagaan anda dalam direktori komuniti Puncak Jalil</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('shops.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Maklumat Asas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shop Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Kedai *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Contoh: Kedai Runcit Ahmad">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori *
                            </label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Kategori</option>
                                @foreach(\App\Models\Shop::getCategories() as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penerangan
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Terangkan tentang perniagaan anda...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Shop Image -->
                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gambar Kedai (Optional)
                            </label>
                            <input type="file" id="image" name="image"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Format yang disokong: JPEG, PNG, JPG, GIF. Maksimum 2MB.</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Maklumat Lokasi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat *
                            </label>
                            <textarea id="address" name="address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Alamat lengkap kedai">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Latitude -->
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Latitude
                            </label>
                            <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="any"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="3.0738">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Longitude
                            </label>
                            <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="any"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="101.5183">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Maklumat Hubungan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombor Telefon
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="03-1234 5678">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                WhatsApp
                            </label>
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="60123456789">
                            @error('whatsapp')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="md:col-span-2">
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Laman Web
                            </label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="https://example.com">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Operating Hours Section -->
                <div class="pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Waktu Operasi</h2>
                    <div>
                        <label for="opening_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Waktu Operasi
                        </label>
                        <textarea id="opening_hours" name="opening_hours" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Contoh:&#10;Isnin - Jumaat: 9:00 AM - 6:00 PM&#10;Sabtu - Ahad: 10:00 AM - 5:00 PM">{{ old('opening_hours') }}</textarea>
                        @error('opening_hours')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('shops.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Kedai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
