@extends('layouts.app')

@section('title', 'Edit Rider')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('riders.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Edit Rider: {{ $rider->name }}</h1>
            </div>

            <form method="POST" action="{{ route('riders.update', $rider) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Penuh <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $rider->name) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama penuh rider" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombor Telefon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $rider->phone) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                               placeholder="Contoh: 0123456789" required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Emel <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $rider->email) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="rider@example.com" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IC Number -->
                    <div>
                        <label for="ic_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombor IC <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="ic_number" name="ic_number" value="{{ old('ic_number', $rider->ic_number) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ic_number') border-red-500 @enderror"
                               placeholder="Contoh: 123456-78-9012" required>
                        @error('ic_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Type -->
                    <div>
                        <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kenderaan <span class="text-red-500">*</span>
                        </label>
                        <select id="vehicle_type" name="vehicle_type"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_type') border-red-500 @enderror" required>
                            <option value="">Pilih jenis kenderaan</option>
                            <option value="motorcycle" {{ old('vehicle_type', $rider->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motosikal</option>
                            <option value="car" {{ old('vehicle_type', $rider->vehicle_type) == 'car' ? 'selected' : '' }}>Kereta</option>
                            <option value="bicycle" {{ old('vehicle_type', $rider->vehicle_type) == 'bicycle' ? 'selected' : '' }}>Basikal</option>
                            <option value="walking" {{ old('vehicle_type', $rider->vehicle_type) == 'walking' ? 'selected' : '' }}>Berjalan</option>
                        </select>
                        @error('vehicle_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Number -->
                    <div>
                        <label for="vehicle_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombor Kenderaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number', $rider->vehicle_number) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_number') border-red-500 @enderror"
                               placeholder="Contoh: ABC1234 atau WXY123" required>
                        @error('vehicle_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                            <option value="active" {{ old('status', $rider->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $rider->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="suspended" {{ old('status', $rider->status) == 'suspended' ? 'selected' : '' }}>Digantung</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Availability -->
                    <div>
                        <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">
                            Ketersediaan <span class="text-red-500">*</span>
                        </label>
                        <select id="availability" name="availability"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('availability') border-red-500 @enderror" required>
                            <option value="available" {{ old('availability', $rider->availability) == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="busy" {{ old('availability', $rider->availability) == 'busy' ? 'selected' : '' }}>Sibuk</option>
                            <option value="offline" {{ old('availability', $rider->availability) == 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                        @error('availability')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                                  placeholder="Masukkan alamat lengkap rider" required>{{ old('address', $rider->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('riders.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        <i class="fas fa-save mr-2"></i>Kemaskini Rider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection