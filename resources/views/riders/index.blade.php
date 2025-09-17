@extends('layouts.app')

@section('title', 'Pengurusan Rider')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Pengurusan Rider</h1>
        <a href="{{ route('riders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-plus mr-2"></i>Tambah Rider
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('riders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Carian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, telefon, email..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Digantung</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ketersediaan</label>
                <select name="availability" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="busy" {{ request('availability') == 'busy' ? 'selected' : '' }}>Sibuk</option>
                    <option value="offline" {{ request('availability') == 'offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kenderaan</label>
                <select name="vehicle_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="motorcycle" {{ request('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motosikal</option>
                    <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>Kereta</option>
                    <option value="bicycle" {{ request('vehicle_type') == 'bicycle' ? 'selected' : '' }}>Basikal</option>
                    <option value="walking" {{ request('vehicle_type') == 'walking' ? 'selected' : '' }}>Berjalan</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('riders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Riders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hubungi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kenderaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penilaian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($riders as $rider)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($rider->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $rider->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $rider->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $rider->phone }}</div>
                            <div class="text-sm text-gray-500">{{ $rider->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $rider->vehicle_type_text }}</div>
                            <div class="text-sm text-gray-500">{{ $rider->vehicle_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($rider->status == 'active') bg-green-100 text-green-800
                                @elseif($rider->status == 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $rider->status_text }}
                            </span>
                            <br>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                                @if($rider->availability == 'available') bg-blue-100 text-blue-800
                                @elseif($rider->availability == 'busy') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $rider->availability_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    @foreach($rider->rating_stars as $star)
                                        @if($star == 'full')
                                            <i class="fas fa-star"></i>
                                        @elseif($star == 'half')
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endforeach
                                </div>
                                <span class="ml-2 text-sm text-gray-600">({{ number_format($rider->rating, 1) }})</span>
                            </div>
                            <div class="text-sm text-gray-500">{{ $rider->total_deliveries }} penghantaran</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('riders.show', $rider) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('riders.edit', $rider) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('riders.destroy', $rider) }}" class="inline"
                                      onsubmit="return confirm('Adakah anda pasti mahu padam rider ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Padam
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tiada rider dijumpai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($riders->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $riders->links() }}
        </div>
        @endif
    </div>
</div>

@endsection