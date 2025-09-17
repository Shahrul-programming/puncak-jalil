@extends('layouts.app')

@section('title', 'Pengurusan Pesanan Vendor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengurusan Pesanan</h1>
            <p class="text-gray-600 mt-2">Urus semua pesanan dari kedai anda</p>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('vendor.orders') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Disahkan</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Sedang Disediakan</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Dihantar</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            @if($shops->count() > 1)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kedai</label>
                <select name="shop_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kedai</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rider</label>
                <select name="rider_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Rider</option>
                    @foreach($allRiders as $rider)
                        <option value="{{ $rider->id }}" {{ request('rider_id') == $rider->id ? 'selected' : '' }}>
                            {{ $rider->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tarikh</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hingga Tarikh</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('vendor.orders') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kedai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->phone ?? 'Tiada nombor' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->shop->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{ route('vendor.orders.update-status', $order) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Disahkan</option>
                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Sedang Disediakan</option>
                                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Dihantar</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->rider)
                                <div class="text-sm text-gray-900">{{ $order->rider->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->rider->phone }}</div>
                                <form method="POST" action="{{ route('vendor.orders.unassign-rider', $order) }}" class="inline mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800"
                                            onclick="return confirm('Adakah anda pasti mahu menarik balik rider ini?')">
                                        <i class="fas fa-times mr-1"></i>Tarik balik
                                    </button>
                                </form>
                            @else
                                <button onclick="openRiderModal({{ $order->id }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-plus mr-1"></i>Tugaskan Rider
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            RM{{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('vendor.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tiada pesanan dijumpai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Rider Assignment Modal -->
<div id="riderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Pilih Rider untuk Pesanan #<span id="orderNumber"></span></h3>
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
let currentOrderId = null;

function openRiderModal(orderId) {
    currentOrderId = orderId;
    document.getElementById('riderModal').classList.remove('hidden');
    document.getElementById('orderNumber').textContent = orderId;
    loadAvailableRiders();
}

function closeRiderModal() {
    document.getElementById('riderModal').classList.add('hidden');
    currentOrderId = null;
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
                            <form method="POST" action="/vendor/orders/${currentOrderId}/assign-rider" class="ml-4">
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