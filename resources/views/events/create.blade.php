@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">📅 Tambah Acara Baru</h1>
            <p class="text-gray-600 dark:text-gray-400">Cipta acara untuk komuniti Puncak Jalil</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Maklumat Asas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Event Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tajuk Acara *
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Contoh: Gotong-royong Kawasan Puncak Jalil">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Acara *
                            </label>
                            <select id="type" name="type" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Jenis</option>
                                <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>🎉 Acara</option>
                                <option value="notis" {{ old('type') == 'notis' ? 'selected' : '' }}>📢 Notis</option>
                                <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>🤝 Mesyuarat</option>
                                <option value="activity" {{ old('type') == 'activity' ? 'selected' : '' }}>🎯 Aktiviti</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tarikh & Masa *
                            </label>
                            <input type="datetime-local" id="date" name="date" value="{{ old('date') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penerangan *
                            </label>
                            <textarea id="description" name="description" rows="6" required
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Terangkan dengan jelas tentang acara ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information Section -->
                <div class="pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Maklumat Lokasi</h2>
                    <div>
                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lokasi *
                            </label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Contoh: Dewan Komuniti Puncak Jalil">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Cipta Acara
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Type Help -->
<div class="max-w-4xl mx-auto mt-6">
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">💡 Panduan Jenis Acara</h3>
        <div class="text-sm text-blue-700 dark:text-blue-400 space-y-1">
            <p><strong>🎉 Acara:</strong> Perayaan, festival, aktiviti hiburan komuniti</p>
            <p><strong>📢 Notis:</strong> Pengumuman penting, pemberitahuan kepada penduduk</p>
            <p><strong>🤝 Mesyuarat:</strong> Mesyuarat penduduk, perbincangan komuniti</p>
            <p><strong>🎯 Aktiviti:</strong> Sukan, kelas, workshop, aktiviti harian</p>
        </div>
    </div>
</div>
@endsection
