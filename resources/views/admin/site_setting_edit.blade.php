@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-12">
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-10 border border-gray-100 dark:border-gray-700">
        <h2 class="text-3xl font-extrabold mb-8 text-center text-blue-700 dark:text-blue-300 tracking-tight">Edit Site Settings</h2>
        @if(session('success'))
            <div class="mb-6 px-4 py-2 rounded bg-green-100 text-green-800 border border-green-300 text-center">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 px-4 py-2 rounded bg-red-100 text-red-800 border border-red-300">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.site-setting.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('POST')
            
            <!-- Headline Text -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="headline">Headline</label>
                <input type="text" name="headline" id="headline" value="{{ old('headline', $setting->headline ?? '') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="Contoh: Selamat Datang ke Puncak Jalil!">
            </div>

            <!-- Description -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="Deskripsi tentang komuniti...">{{ old('description', $setting->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Font Size -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="headline_font_size">Font Size</label>
                    <select name="headline_font_size" id="headline_font_size" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="text-2xl" {{ old('headline_font_size', $setting->headline_font_size ?? '') == 'text-2xl' ? 'selected' : '' }}>Small (2xl)</option>
                        <option value="text-3xl" {{ old('headline_font_size', $setting->headline_font_size ?? '') == 'text-3xl' ? 'selected' : '' }}>Medium (3xl)</option>
                        <option value="text-4xl" {{ old('headline_font_size', $setting->headline_font_size ?? '') == 'text-4xl' ? 'selected' : '' }}>Large (4xl)</option>
                        <option value="text-5xl" {{ old('headline_font_size', $setting->headline_font_size ?? '') == 'text-5xl' ? 'selected' : '' }}>Extra Large (5xl)</option>
                        <option value="text-6xl" {{ old('headline_font_size', $setting->headline_font_size ?? '') == 'text-6xl' ? 'selected' : '' }}>Huge (6xl)</option>
                    </select>
                </div>

                <!-- Text Color -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="headline_color">Text Color</label>
                    <select name="headline_color" id="headline_color" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="text-gray-900" {{ old('headline_color', $setting->headline_color ?? '') == 'text-gray-900' ? 'selected' : '' }}>Dark Gray</option>
                        <option value="text-blue-600" {{ old('headline_color', $setting->headline_color ?? '') == 'text-blue-600' ? 'selected' : '' }}>Blue</option>
                        <option value="text-green-600" {{ old('headline_color', $setting->headline_color ?? '') == 'text-green-600' ? 'selected' : '' }}>Green</option>
                        <option value="text-purple-600" {{ old('headline_color', $setting->headline_color ?? '') == 'text-purple-600' ? 'selected' : '' }}>Purple</option>
                        <option value="text-red-600" {{ old('headline_color', $setting->headline_color ?? '') == 'text-red-600' ? 'selected' : '' }}>Red</option>
                        <option value="text-yellow-600" {{ old('headline_color', $setting->headline_color ?? '') == 'text-yellow-600' ? 'selected' : '' }}>Yellow</option>
                        <option value="text-white" {{ old('headline_color', $setting->headline_color ?? '') == 'text-white' ? 'selected' : '' }}>White</option>
                    </select>
                </div>

                <!-- Text Alignment -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="headline_alignment">Text Alignment</label>
                    <select name="headline_alignment" id="headline_alignment" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="text-left" {{ old('headline_alignment', $setting->headline_alignment ?? '') == 'text-left' ? 'selected' : '' }}>Left</option>
                        <option value="text-center" {{ old('headline_alignment', $setting->headline_alignment ?? '') == 'text-center' ? 'selected' : '' }}>Center</option>
                        <option value="text-right" {{ old('headline_alignment', $setting->headline_alignment ?? '') == 'text-right' ? 'selected' : '' }}>Right</option>
                    </select>
                </div>
            </div>

            <!-- Background Color -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="background_color">Background Color</label>
                <select name="background_color" id="background_color" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option value="" {{ old('background_color', $setting->background_color ?? '') == '' ? 'selected' : '' }}>Default (White)</option>
                    <option value="bg-blue-50" {{ old('background_color', $setting->background_color ?? '') == 'bg-blue-50' ? 'selected' : '' }}>Light Blue</option>
                    <option value="bg-green-50" {{ old('background_color', $setting->background_color ?? '') == 'bg-green-50' ? 'selected' : '' }}>Light Green</option>
                    <option value="bg-purple-50" {{ old('background_color', $setting->background_color ?? '') == 'bg-purple-50' ? 'selected' : '' }}>Light Purple</option>
                    <option value="bg-gray-100" {{ old('background_color', $setting->background_color ?? '') == 'bg-gray-100' ? 'selected' : '' }}>Light Gray</option>
                    <option value="bg-gradient-to-r from-blue-500 to-purple-600" {{ old('background_color', $setting->background_color ?? '') == 'bg-gradient-to-r from-blue-500 to-purple-600' ? 'selected' : '' }}>Blue to Purple Gradient</option>
                </select>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="contact_phone">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $setting->contact_phone ?? '') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="03-1234 5678">
                </div>

                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="contact_email">Contact Email</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $setting->contact_email ?? '') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="info@puncakjalil.com">
                </div>
            </div>

            <!-- Headline Image -->
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="headline_image">Gambar Headline (optional)</label>
                <input type="file" name="headline_image" id="headline_image" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                @if(!empty($setting->headline_image))
                    <div class="mt-4 flex flex-col items-center">
                        <img src="{{ asset('storage/' . $setting->headline_image) }}" alt="Headline Image" class="max-h-40 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 mt-2">Gambar sedia ada</span>
                    </div>
                @endif
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg shadow transition">Simpan Semua</button>
            </div>
        </form>
    </div>
</div>
@endsection
