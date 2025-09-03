@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('reports.index') }}" 
                       class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Detail Laporan #{{ $report->id }}
                    </h1>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-2">
                    @if(auth()->user()->is_admin || $report->user_id === auth()->id())
                        @if($report->status !== 'resolved')
                            <a href="{{ route('reports.edit', $report) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                        @endif
                    @endif
                    
                    @if(auth()->user()->is_admin)
                        <div class="flex items-center space-x-2">
                            <form method="POST" action="{{ route('admin.reports.updateStatus', $report) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                        class="text-sm rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                    <option value="baru" {{ $report->status === 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="dalam_tindakan" {{ $report->status === 'dalam_tindakan' ? 'selected' : '' }}>Dalam Tindakan</option>
                                    <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Report Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <!-- Status & Category -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $report->status_color }}">
                                <i class="{{ $report->status_icon }} mr-1"></i>
                                {{ $report->status_text }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $report->category_name }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $report->time_ago }}
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            <i class="fas fa-edit text-red-600 mr-2"></i>Penerangan Masalah
                        </h3>
                        <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->description }}</div>
                    </div>

                    <!-- Location -->
                    @if($report->location)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>Lokasi
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->location }}</p>
                        </div>
                    @endif

                    <!-- Image -->
                    @if($report->image)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <i class="fas fa-camera text-red-600 mr-2"></i>Gambar
                            </h3>
                            <div class="mt-2">
                                <img src="{{ Storage::url($report->image) }}" 
                                     alt="Gambar laporan" 
                                     class="max-w-full h-auto rounded-lg shadow-sm cursor-pointer"
                                     onclick="openImageModal('{{ Storage::url($report->image) }}')">
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Updates/Comments Section (if needed in future) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-comments text-red-600 mr-2"></i>Kemaskini Status
                    </h3>
                    
                    @if($report->status === 'baru')
                        <div class="flex items-center text-blue-600 dark:text-blue-400">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Laporan telah diterima dan sedang menunggu tindakan</span>
                        </div>
                    @elseif($report->status === 'dalam_tindakan')
                        <div class="flex items-center text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-tools mr-2"></i>
                            <span>Laporan sedang dalam tindakan untuk diselesaikan</span>
                        </div>
                    @else
                        <div class="flex items-center text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Laporan telah diselesaikan dengan jayanya</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Reporter Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-user text-red-600 mr-2"></i>Maklumat Pelapor
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600 dark:text-red-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $report->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->user->email }}</p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-calendar mr-1"></i>
                                Dilaporkan: {{ $report->created_at->format('d M Y, g:i A') }}
                            </p>
                            @if($report->updated_at != $report->created_at)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    Dikemaskini: {{ $report->updated_at->format('d M Y, g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Report Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-chart-line text-red-600 mr-2"></i>Statistik
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">ID Laporan:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $report->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Kategori:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->category_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->status_text }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Masa Aktif:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->time_ago }}</span>
                        </div>
                    </div>
                </div>

                <!-- Emergency Notice -->
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 p-4">
                    <div class="flex items-start">
                        <i class="fas fa-phone text-red-600 dark:text-red-400 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">
                                Kecemasan
                            </h4>
                            <p class="text-xs text-red-700 dark:text-red-300">
                                Untuk situasi kecemasan, sila hubungi 999 atau authorities yang berkaitan secara terus.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full rounded-lg">
        <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = src;
    modal.classList.remove('hidden');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection
