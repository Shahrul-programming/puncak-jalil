@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Kedai</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Kemaskini maklumat kedai anda</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('shops.update', $shop) }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Maklumat Asas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shop Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Kedai *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $shop->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Masukkan nama kedai">
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
                                <option value="">Pilih kategori</option>
                                <option value="Makanan" {{ old('category', $shop->category) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                <option value="Pakaian" {{ old('category', $shop->category) == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                                <option value="Elektronik" {{ old('category', $shop->category) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Kecantikan" {{ old('category', $shop->category) == 'Kecantikan' ? 'selected' : '' }}>Kecantikan</option>
                                <option value="Rumah & Taman" {{ old('category', $shop->category) == 'Rumah & Taman' ? 'selected' : '' }}>Rumah & Taman</option>
                                <option value="Perkhidmatan" {{ old('category', $shop->category) == 'Perkhidmatan' ? 'selected' : '' }}>Perkhidmatan</option>
                                <option value="Lain-lain" {{ old('category', $shop->category) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombor Telefon *
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $shop->phone) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="60123456789">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                WhatsApp (Optional)
                            </label>
                            <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="60123456789">
                            @error('whatsapp')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="md:col-span-2">
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Website (Optional)
                            </label>
                            <input type="url" id="website" name="website" value="{{ old('website', $shop->website) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="https://example.com">
                            @error('website')
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
                                      placeholder="Terangkan tentang perniagaan anda...">{{ old('description', $shop->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image & New Image Upload -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gambar Kedai
                            </label>
                            
                            @if($shop->image)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar Semasa:</p>
                                    <img src="{{ Storage::url($shop->image) }}" alt="{{ $shop->name }}" 
                                         class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                </div>
                            @endif
                            
                            <input type="file" id="image" name="image"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Format yang disokong: JPEG, PNG, JPG, GIF. Maksimum 2MB. 
                                {{ $shop->image ? 'Pilih gambar baru untuk menggantikan gambar semasa.' : '' }}
                            </p>
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
                                      placeholder="Masukkan alamat lengkap kedai">{{ old('address', $shop->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Negeri *
                            </label>
                            <select id="state" name="state" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih negeri</option>
                                <option value="Johor" {{ old('state', $shop->state) == 'Johor' ? 'selected' : '' }}>Johor</option>
                                <option value="Kedah" {{ old('state', $shop->state) == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                <option value="Kelantan" {{ old('state', $shop->state) == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                <option value="Kuala Lumpur" {{ old('state', $shop->state) == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                <option value="Labuan" {{ old('state', $shop->state) == 'Labuan' ? 'selected' : '' }}>Labuan</option>
                                <option value="Melaka" {{ old('state', $shop->state) == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                <option value="Negeri Sembilan" {{ old('state', $shop->state) == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                <option value="Pahang" {{ old('state', $shop->state) == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                <option value="Perak" {{ old('state', $shop->state) == 'Perak' ? 'selected' : '' }}>Perak</option>
                                <option value="Perlis" {{ old('state', $shop->state) == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                <option value="Pulau Pinang" {{ old('state', $shop->state) == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                <option value="Putrajaya" {{ old('state', $shop->state) == 'Putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                                <option value="Sabah" {{ old('state', $shop->state) == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                <option value="Sarawak" {{ old('state', $shop->state) == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                <option value="Selangor" {{ old('state', $shop->state) == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                <option value="Terengganu" {{ old('state', $shop->state) == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                            </select>
                            @error('state')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postcode -->
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Poskod *
                            </label>
                            <input type="text" id="postcode" name="postcode" value="{{ old('postcode', $shop->postcode) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="12345">
                            @error('postcode')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Operating Hours Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Waktu Operasi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Opening Time -->
                        <div>
                            <label for="opening_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Masa Buka *
                            </label>
                            <input type="time" id="opening_time" name="opening_time" value="{{ old('opening_time', $shop->opening_time) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('opening_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Closing Time -->
                        <div>
                            <label for="closing_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Masa Tutup *
                            </label>
                            <input type="time" id="closing_time" name="closing_time" value="{{ old('closing_time', $shop->closing_time) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('closing_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center pt-6">
                    <a href="{{ route('shops.show', $shop) }}" 
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Kemaskini Kedai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
