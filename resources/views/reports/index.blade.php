@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Laporan Komuniti
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Laporkan isu dan masalah dalam komuniti Puncak Jalil</p>
            </div>
            <div class="flex space-x-3">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('reports.admin') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-cog mr-2"></i>Admin Panel
                    </a>
                @endif
                <a href="{{ route('reports.create') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Buat Laporan
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Laporan</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalReports }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900/20 rounded-lg">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Baru</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $openReports }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Dalam Tindakan</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $inProgressReports }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $resolvedReports }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1"></i>Cari
                </label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ $search }}"
                       placeholder="Cari dalam deskripsi atau lokasi..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500">
            </div>
            <div class="sm:w-48">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-tag mr-1"></i>Kategori
                </label>
                <select id="category" 
                        name="category"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $name)
                        <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-flag mr-1"></i>Status
                </label>
                <select id="status" 
                        name="status"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $name)
                        <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if($search || $category || $status)
                    <a href="{{ route('reports.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <!-- Report Header -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                   bg-{{ $report->status_color }}-100 text-{{ $report->status_color }}-800 
                                   dark:bg-{{ $report->status_color }}-900/20 dark:text-{{ $report->status_color }}-400">
                            <i class="{{ $report->status_icon }} mr-1"></i>
                            {{ $report->status_name }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            {{ $report->category_name }}
                        </span>
                    </div>

                    <!-- Report Content -->
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        {{ $report->category_name }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-3">
                        {{ Str::limit($report->description, 120) }}
                    </p>

                    @if($report->location)
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <span>{{ $report->location }}</span>
                        </div>
                    @endif

                    @if($report->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $report->image) }}" 
                                 alt="Gambar laporan" 
                                 class="w-full h-32 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Report Footer -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user mr-1"></i>
                            <span class="font-medium">{{ $report->user->name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $report->time_ago }}</span>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('reports.show', $report) }}" 
                               class="text-blue-600 hover:text-blue-700 p-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                               title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(Auth::user()->role === 'admin' || Auth::id() === $report->user_id)
                                @if($report->status === 'open' || Auth::user()->role === 'admin')
                                    <a href="{{ route('reports.edit', $report) }}" 
                                       class="text-green-600 hover:text-green-700 p-1 rounded hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Adakah anda pasti untuk memadam laporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                                            title="Padam">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Tiada laporan ditemui</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    @if($search || $category || $status)
                        Tiada laporan yang sepadan dengan kriteria carian anda.
                    @else
                        Belum ada laporan. Laporkan isu komuniti untuk tindakan yang sewajarnya.
                    @endif
                </p>
                @if($search || $category || $status)
                    <a href="{{ route('reports.index') }}" 
                       class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200 mr-3">
                        <i class="fas fa-times mr-2"></i>Reset Carian
                    </a>
                @endif
                <a href="{{ route('reports.create') }}" 
                   class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Buat Laporan Pertama
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reports->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $reports->withQueryString()->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection
