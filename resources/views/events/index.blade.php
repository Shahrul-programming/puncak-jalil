@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">📅 Acara Komuniti</h1>
            <p class="text-gray-600 dark:text-gray-400">Jangan terlepas acara menarik di Puncak Jalil</p>
        </div>
        @auth
            <a href="{{ route('events.create') }}" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Acara
            </a>
        @endauth
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('events.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari tajuk acara, penerangan, atau lokasi..." 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <!-- Type Filter -->
            <div class="md:w-48">
                <select name="type" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Jenis</option>
                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Acara</option>
                    <option value="notis" {{ request('type') == 'notis' ? 'selected' : '' }}>Notis</option>
                    <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Mesyuarat</option>
                    <option value="activity" {{ request('type') == 'activity' ? 'selected' : '' }}>Aktiviti</option>
                </select>
            </div>
            
            <!-- Date Filter -->
            <div class="md:w-48">
                <select name="date_filter" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tarikh</option>
                    <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>Lepas</option>
                </select>
            </div>
            
            <!-- Search Button -->
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
        </form>
    </div>

    <!-- Quick Navigation Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-lg {{ !request()->filled('date_filter') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('events.index', ['date_filter' => 'upcoming']) }}" class="px-4 py-2 rounded-lg {{ request('date_filter') == 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Akan Datang
        </a>
        <a href="{{ route('events.index', ['date_filter' => 'today']) }}" class="px-4 py-2 rounded-lg {{ request('date_filter') == 'today' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Hari Ini
        </a>
        <a href="{{ route('events.index', ['type' => 'event']) }}" class="px-4 py-2 rounded-lg {{ request('type') == 'event' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Acara
        </a>
        <a href="{{ route('events.index', ['type' => 'notis']) }}" class="px-4 py-2 rounded-lg {{ request('type') == 'notis' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Notis
        </a>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-gray-600 dark:text-gray-400">
            Menunjukkan {{ $events->count() }} acara
            @if(request('search') || request('type') || request('date_filter'))
                daripada {{ $events->total() ?? $events->count() }} jumlah acara
            @endif
        </p>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 {{ $event->is_today ? 'ring-2 ring-blue-500' : '' }}">
                <!-- Event Header -->
                <div class="p-6">
                    <!-- Type Badge & Date -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                            @if($event->type === 'event') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($event->type === 'notis') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @elseif($event->type === 'meeting') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                            @endif">
                            @if($event->type === 'event') 🎉 Acara
                            @elseif($event->type === 'notis') 📢 Notis
                            @elseif($event->type === 'meeting') 🤝 Mesyuarat
                            @else 🎯 Aktiviti
                            @endif
                        </span>
                        
                        @if($event->is_today)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded-full animate-pulse">
                                HARI INI
                            </span>
                        @elseif($event->is_upcoming)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full">
                                AKAN DATANG
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 rounded-full">
                                LEPAS
                            </span>
                        @endif
                    </div>

                    <!-- Event Title -->
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">{{ $event->title }}</h3>

                    <!-- Description -->
                    @if($event->description)
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">{{ $event->description }}</p>
                    @endif

                    <!-- Date & Time -->
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">{{ $event->display_date }}</span>
                    </div>

                    <!-- Location -->
                    @if($event->location)
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="line-clamp-1">{{ $event->location }}</span>
                        </div>
                    @endif

                    <!-- Organizer -->
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Oleh: {{ $event->user->name }}</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('events.show', $event->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                            Lihat Detail
                        </a>
                        
                        @php $user = auth()->user(); @endphp
                        @if($user && ($user->role === 'admin' || $event->user_id === $user->id))
                            <a href="{{ route('events.edit', $event->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Padam acara ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                    Padam
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tiada acara dijumpai</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if(request('search') || request('type') || request('date_filter'))
                            Cuba ubah kriteria pencarian anda.
                        @else
                            Belum ada acara yang dijadualkan.
                        @endif
                    </p>
                    @auth
                        @if(!request('search') && !request('type') && !request('date_filter'))
                            <div class="mt-6">
                                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Acara Pertama
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($events, 'links'))
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @endif
</div>

<!-- Custom CSS for line-clamp -->
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
