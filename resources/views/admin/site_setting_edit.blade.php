@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-12">
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-10 border border-gray-100 dark:border-gray-700">
        <h2 class="text-3xl font-extrabold mb-8 text-center text-blue-700 dark:text-blue-300 tracking-tight">Edit Headline & Gambar Headline</h2>
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
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200" for="headline">Headline</label>
                <input type="text" name="headline" id="headline" value="{{ old('headline', $setting->headline ?? '') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="Contoh: Selamat Datang ke Puncak Jalil!">
            </div>
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
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg shadow transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
