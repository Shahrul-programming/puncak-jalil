@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('events.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Senarai Acara
            </a>
        </div>

        <!-- Event Detail Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Event Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-12 text-white">
                <div class="flex items-start justify-between mb-4">
                    <!-- Event Type Badge -->
                    <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-white bg-opacity-20">
                        @if($event->type === 'event') 🎉 Acara
                        @elseif($event->type === 'notis') 📢 Notis
                        @elseif($event->type === 'meeting') 🤝 Mesyuarat
                        @else 🎯 Aktiviti
                        @endif
                    </span>
                    
                    <!-- Status Badge -->
                    @if($event->is_today)
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-red-500 text-white rounded-full animate-pulse">
                            HARI INI
                        </span>
                    @elseif($event->is_upcoming)
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-green-500 text-white rounded-full">
                            AKAN DATANG
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-gray-500 text-white rounded-full">
                            TELAH BERLALU
                        </span>
                    @endif
                </div>
                
                <h1 class="text-4xl font-bold mb-4">{{ $event->title }}</h1>
                
                <!-- Quick Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $event->formatted_date }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $event->user->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Event Content -->
            <div class="p-8">
                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Maklumat Terperinci</h2>
                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
                    </div>
                </div>

                <!-- Event Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Date & Time Details -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tarikh & Masa
                        </h3>
                        <div class="space-y-2 text-gray-600 dark:text-gray-400">
                            <p><strong>Tarikh:</strong> {{ $event->date->format('l, d F Y') }}</p>
                            <p><strong>Masa:</strong> {{ $event->date->format('g:i A') }}</p>
                            <p><strong>Status:</strong> {{ $event->time_until }}</p>
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lokasi
                        </h3>
                        <div class="space-y-2 text-gray-600 dark:text-gray-400">
                            <p>{{ $event->location }}</p>
                            <a href="https://www.google.com/maps/search/{{ urlencode($event->location . ' Puncak Jalil') }}" 
                               target="_blank" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lihat di Google Maps
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Organizer Info -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-2">Penganjur</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($event->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-blue-900 dark:text-blue-300">{{ $event->user->name }}</p>
                            <p class="text-sm text-blue-700 dark:text-blue-400">Ahli Komuniti Puncak Jalil</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    @if($event->is_upcoming)
                        <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Tambah ke Kalendar
                        </button>
                    @endif
                    
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Kongsi
                    </button>
                    
                    @php $user = auth()->user(); @endphp
                    @if($user && ($user->role === 'admin' || $event->user_id === $user->id))
                        <a href="{{ route('events.edit', $event->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Acara
                        </a>
                        
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Adakah anda pasti ingin memadam acara ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Padam
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Events -->
        @if($relatedEvents && $relatedEvents->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Acara Berkaitan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedEvents as $relatedEvent)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($relatedEvent->type === 'event') bg-green-100 text-green-800
                                    @elseif($relatedEvent->type === 'notis') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($relatedEvent->type) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $relatedEvent->date->format('M d') }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $relatedEvent->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $relatedEvent->description }}</p>
                            <a href="{{ route('events.show', $relatedEvent->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Detail →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
