@extends('layouts.app')

@section('title', 'Butiran Rider')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('riders.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $rider->name }}</h1>
                        <p class="text-gray-600">ID Rider: {{ $rider->id }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('riders.edit', $rider) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('riders.destroy', $rider) }}" class="inline"
                          onsubmit="return confirm('Adakah anda pasti mahu padam rider ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                            <i class="fas fa-trash mr-2"></i>Padam
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Rider Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Maklumat Rider</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Maklumat Peribadi</h3>
                            <div class="mt-2 space-y-2">
                                <p><span class="font-medium">Nama:</span> {{ $rider->name }}</p>
                                <p><span class="font-medium">Telefon:</span> {{ $rider->phone }}</p>
                                <p><span class="font-medium">Emel:</span> {{ $rider->email }}</p>
                                <p><span class="font-medium">IC:</span> {{ $rider->ic_number }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Maklumat Kenderaan</h3>
                            <div class="mt-2 space-y-2">
                                <p><span class="font-medium">Jenis:</span> {{ $rider->vehicle_type_text }}</p>
                                <p><span class="font-medium">Nombor:</span> {{ $rider->vehicle_number }}</p>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Alamat</h3>
                            <div class="mt-2">
                                <p>{{ $rider->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pesanan Terkini</h2>

                    @if($rider->orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($rider->orders->take(5) as $order)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">Pesanan #{{ $order->id }}</h3>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'delivered') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'out_for_delivery') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                                        @elseif($order->status == 'confirmed') bg-indigo-100 text-indigo-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm font-medium">RM{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($rider->orders->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua pesanan</a>
                        </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">Tiada pesanan dijumpai.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Status & Rating -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Status & Penilaian</h2>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Status</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                                @if($rider->status == 'active') bg-green-100 text-green-800
                                @elseif($rider->status == 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $rider->status_text }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700">Ketersediaan</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                                @if($rider->availability == 'available') bg-blue-100 text-blue-800
                                @elseif($rider->availability == 'busy') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $rider->availability_text }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700">Penilaian</p>
                            <div class="flex items-center mt-1">
                                <div class="flex text-yellow-400 mr-2">
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
                                <span class="text-sm text-gray-600">({{ number_format($rider->rating, 1) }})</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700">Jumlah Penghantaran</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $rider->total_deliveries }}</p>
                        </div>
                    </div>
                </div>

                <!-- Location Info -->
                @if($rider->latitude && $rider->longitude)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Lokasi</h2>

                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Latitude:</span> {{ $rider->latitude }}</p>
                        <p class="text-sm"><span class="font-medium">Longitude:</span> {{ $rider->longitude }}</p>
                        @if($rider->last_location_update)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Kemaskini Terakhir:</span><br>
                            {{ $rider->last_location_update->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection