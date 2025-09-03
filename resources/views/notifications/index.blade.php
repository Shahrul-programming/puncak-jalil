@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifikasi</h1>
                    <div class="flex space-x-3">
                        <button id="markAllRead" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                            Tandai Semua Dibaca
                        </button>
                        <button id="clearAll" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                            Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($notifications as $notification)
                    <div class="p-6 {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }} hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    @if($notification->read_at === null)
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    @else
                                        <div class="w-2 h-2 mr-3"></div>
                                    @endif
                                    
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                    </h3>
                                    
                                    @if($notification->read_at === null)
                                        <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full">
                                            Baru
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="mt-2 text-gray-600 dark:text-gray-300">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                
                                @if(isset($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm">
                                        Lihat Detail →
                                    </a>
                                @endif
                                
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                @if($notification->read_at === null)
                                    <button onclick="markAsRead('{{ $notification->id }}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm">
                                        Tandai Dibaca
                                    </button>
                                @endif
                                
                                <button onclick="deleteNotification('{{ $notification->id }}')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 text-sm">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3-3m-5.5 0a6.5 6.5 0 110-13 6.5 6.5 0 010 13z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada notifikasi</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Anda belum memiliki notifikasi apapun.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'PATCH',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Gagal menandai notifikasi sebagai dibaca');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        }
    }

    async function deleteNotification(notificationId) {
        if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
            return;
        }

        try {
            const response = await fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Gagal menghapus notifikasi');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        }
    }

    document.getElementById('markAllRead').addEventListener('click', async function() {
        try {
            const response = await fetch('/notifications/read-all', {
                method: 'PATCH',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Gagal menandai semua notifikasi sebagai dibaca');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        }
    });

    document.getElementById('clearAll').addEventListener('click', async function() {
        if (!confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) {
            return;
        }

        try {
            const response = await fetch('/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Gagal menghapus semua notifikasi');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        }
    });
</script>
@endsection
