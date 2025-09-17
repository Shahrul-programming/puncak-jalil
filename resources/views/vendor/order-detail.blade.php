@extends('layouts.app')

@section('title', 'Butiran Pesanan Vendor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('vendor.orders') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pesanan #{{ $order->order_number }}</h1>
                        <p class="text-gray-600">{{ $order->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($order->status == 'pending') bg-gray-100 text-gray-800
                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                        @elseif($order->status == 'ready') bg-green-100 text-green-800
                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $order->status_text }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Item Pesanan</h2>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->menuItem->name }}</h3>
                                <p class="text-sm text-gray-600">RM{{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                                @if($item->special_instructions)
                                    <p class="text-sm text-gray-500 italic">Nota: {{ $item->special_instructions }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">RM{{ number_format($item->total, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Jumlah kecil:</span>
                                <span>RM{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Yuran penghantaran:</span>
                                <span>RM{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg border-t border-gray-300 pt-2">
                                <span>Jumlah keseluruhan:</span>
                                <span>RM{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Maklumat Penghantaran</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Alamat penghantaran:</p>
                            <p class="text-gray-900">{{ $order->delivery_address }}</p>
                        </div>
                        @if($order->special_instructions)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Arahan khas:</p>
                            <p class="text-gray-900">{{ $order->special_instructions }}</p>
                        </div>
                        @endif
                        @if($order->requested_delivery_time)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Masa penghantaran diminta:</p>
                            <p class="text-gray-900">{{ $order->requested_delivery_time->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        @if($order->estimated_delivery_time)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Anggaran masa penghantaran:</p>
                            <p class="text-gray-900">{{ $order->estimated_delivery_time->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        @if($order->delivered_at)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Masa penghantaran selesai:</p>
                            <p class="text-gray-900">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Tindakan Cepat</h2>
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('vendor.orders.update-status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kemaskini Status</label>
                                <select name="status" onchange="this.form.submit()"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Disahkan</option>
                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Sedang Disediakan</option>
                                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Dihantar</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Rider Assignment -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pengurusan Rider</h2>

                    @if($order->rider)
                        <!-- Current Rider Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h3 class="font-medium text-blue-900 mb-2">Rider Ditugaskan</h3>
                            <div class="space-y-1">
                                <p class="text-sm text-blue-800"><strong>{{ $order->rider->name }}</strong></p>
                                <p class="text-sm text-blue-700">{{ $order->rider->phone }}</p>
                                <p class="text-sm text-blue-700">{{ $order->rider->vehicle_type_text }}</p>
                                <div class="flex items-center mt-2">
                                    <div class="flex text-yellow-400">
                                        @foreach($order->rider->rating_stars as $star)
                                            @if($star == 'full')
                                                <i class="fas fa-star"></i>
                                            @elseif($star == 'half')
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endforeach
                                    </div>
                                    <span class="ml-2 text-sm text-blue-700">({{ number_format($order->rider->rating, 1) }})</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <form method="POST" action="{{ route('vendor.orders.unassign-rider', $order) }}"
                                      onsubmit="return confirm('Adakah anda pasti mahu menarik balik rider ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-times mr-2"></i>Tarik Balik Rider
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Assign Rider -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <h3 class="font-medium text-gray-900 mb-2">Tiada Rider Ditugaskan</h3>
                            <p class="text-sm text-gray-600 mb-3">Tugaskan rider untuk penghantaran pesanan ini.</p>

                            <button type="button" onclick="openRiderModal()"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user-plus mr-2"></i>Tugaskan Rider
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Maklumat Pelanggan</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Nama:</span> {{ $order->user->name }}</p>
                        <p><span class="font-medium">Emel:</span> {{ $order->user->email }}</p>
                        @if($order->user->phone)
                            <p><span class="font-medium">Telefon:</span> {{ $order->user->phone }}</p>
                        @endif
                    </div>
                </div>

                <!-- Shop Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Maklumat Kedai</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Nama Kedai:</span> {{ $order->shop->name }}</p>
                        <p><span class="font-medium">Alamat:</span> {{ $order->shop->address }}</p>
                        <p><span class="font-medium">Telefon:</span> {{ $order->shop->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rider Assignment Modal -->
<div id="riderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Pilih Rider untuk Pesanan #{{ $order->order_number }}</h3>
                <button onclick="closeRiderModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="ridersList" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- Riders will be loaded here -->
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="closeRiderModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-2">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openRiderModal() {
    document.getElementById('riderModal').classList.remove('hidden');
    loadAvailableRiders();
}

function closeRiderModal() {
    document.getElementById('riderModal').classList.add('hidden');
}

function loadAvailableRiders() {
    fetch(`{{ route('vendor.available-riders') }}`)
        .then(response => response.json())
        .then(data => {
            const ridersList = document.getElementById('ridersList');
            ridersList.innerHTML = '';

            if (data.riders.length === 0) {
                ridersList.innerHTML = '<p class="text-gray-500 text-center py-4">Tiada rider tersedia.</p>';
                return;
            }

            data.riders.forEach(rider => {
                const riderCard = `
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">${rider.name}</h4>
                                <p class="text-sm text-gray-600">${rider.phone}</p>
                                <p class="text-sm text-gray-600">${rider.vehicle_type_text}</p>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400">
                                        ${generateStars(rider.rating)}
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">(${rider.rating})</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">${rider.total_deliveries} penghantaran</p>
                                ${rider.distance ? `<p class="text-sm text-blue-600 mt-1">${rider.distance} km dari kedai</p>` : ''}
                            </div>
                            <form method="POST" action="{{ route('vendor.orders.assign-rider', $order) }}" class="ml-4">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="rider_id" value="${rider.id}">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i>Pilih
                                </button>
                            </form>
                        </div>
                    </div>
                `;
                ridersList.innerHTML += riderCard;
            });
        })
        .catch(error => {
            console.error('Error loading riders:', error);
            document.getElementById('ridersList').innerHTML = '<p class="text-red-500 text-center py-4">Gagal memuatkan rider.</p>';
        });
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star text-xs"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt text-xs"></i>';
        } else {
            stars += '<i class="far fa-star text-xs"></i>';
        }
    }
    return stars;
}
</script>

@endsection