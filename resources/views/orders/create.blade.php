@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Buat Pesanan</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Pilih makanan dari {{ $shop->name }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Menu Items -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Menu</h2>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="filterCategory('all')"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors category-filter active"
                                    data-category="all">
                                Semua
                            </button>
                            @foreach($shop->menuItems->pluck('category')->unique() as $category)
                                <button onclick="filterCategory('{{ $category }}')"
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors category-filter"
                                        data-category="{{ $category }}">
                                    {{ $category }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Menu Items Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="menu-items">
                        @forelse($shop->menuItems as $item)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 menu-item {{ $item->category }}"
                                 data-category="{{ $item->category }}">
                                <div class="flex items-start space-x-4">
                                    @if($item->image)
                                        <img src="{{ $item->image_url }}"
                                             alt="{{ $item->name }}"
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-400 text-xl"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $item->description }}</p>

                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-blue-600">{{ $item->formatted_price }}</span>
                                            @if($item->is_available)
                                                <button onclick="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                                        class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                    <i class="fas fa-plus mr-1"></i>Tambah
                                                </button>
                                            @else
                                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-lg text-sm">Tidak Tersedia</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center space-x-2 mt-2">
                                            @if($item->is_vegetarian)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Vegetarian</span>
                                            @endif
                                            @if($item->is_spicy)
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Pedas</span>
                                            @endif
                                            @if($item->is_halal)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Halal</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-utensils text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tiada Menu</h3>
                                <p class="text-gray-600 dark:text-gray-400">Kedai ini belum menambah sebarang menu</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Ringkasan Pesanan</h2>

                    <!-- Cart Items -->
                    <div id="cart-items" class="space-y-4 mb-6">
                        <p class="text-gray-600 dark:text-gray-400 text-center">Tiada item dalam troli</p>
                    </div>

                    <!-- Order Total -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                            <span id="subtotal" class="font-semibold">RM 0.00</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600 dark:text-gray-400">Penghantaran:</span>
                            <span id="delivery-fee" class="font-semibold">RM 0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span class="text-gray-900 dark:text-white">Jumlah:</span>
                            <span id="total" class="text-blue-600">RM 0.00</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button id="checkout-btn"
                            class="w-full mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                            disabled>
                        Pesan Sekarang
                    </button>

                    <!-- Back Button -->
                    <a href="{{ route('orders.create') }}"
                       class="w-full mt-3 inline-block text-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Pilih Kedai Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form for Checkout -->
<form id="checkout-form" method="POST" action="{{ route('orders.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
    <div id="cart-form-data"></div>
</form>

<script>
let cart = {};

function addToCart(itemId, itemName, price) {
    if (!cart[itemId]) {
        cart[itemId] = {
            id: itemId,
            name: itemName,
            price: price,
            quantity: 0
        };
    }
    cart[itemId].quantity++;
    updateCartDisplay();
}

function removeFromCart(itemId) {
    if (cart[itemId]) {
        cart[itemId].quantity--;
        if (cart[itemId].quantity <= 0) {
            delete cart[itemId];
        }
        updateCartDisplay();
    }
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const cartFormData = document.getElementById('cart-form-data');
    const checkoutBtn = document.getElementById('checkout-btn');

    let html = '';
    let formHtml = '';
    let subtotal = 0;
    let itemCount = 0;

    for (const [itemId, item] of Object.entries(cart)) {
        if (item.quantity > 0) {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            itemCount += item.quantity;

            html += `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 dark:text-white">${item.name}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">RM ${item.price.toFixed(2)} x ${item.quantity}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="removeFromCart(${itemId})"
                                class="w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="font-semibold">${item.quantity}</span>
                        <button onclick="addToCart(${itemId}, '${item.name}', ${item.price})"
                                class="w-8 h-8 bg-green-100 text-green-600 rounded-full hover:bg-green-200 transition-colors">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
                <div class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                    RM ${itemTotal.toFixed(2)}
                </div>
            `;

            formHtml += `
                <div class="items[${itemCount}]">
                    <input type="hidden" name="items[${itemCount}][menu_item_id]" value="${itemId}">
                    <input type="hidden" name="items[${itemCount}][quantity]" value="${item.quantity}">
                    <input type="text" name="items[${itemCount}][special_requests]" placeholder="Arahan khas (pilihan)" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            `;
        }
    }

    if (html === '') {
        html = '<p class="text-gray-600 dark:text-gray-400 text-center">Tiada item dalam troli</p>';
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Pesan Sekarang';
    } else {
        checkoutBtn.disabled = false;
        checkoutBtn.textContent = `Pesan Sekarang (${itemCount} item)`;
    }

    cartItems.innerHTML = html;
    cartFormData.innerHTML = formHtml;

    // Calculate delivery fee and total
    const deliveryFee = subtotal > 50 ? 0 : 5.00;
    const total = subtotal + deliveryFee;

    document.getElementById('subtotal').textContent = `RM ${subtotal.toFixed(2)}`;
    document.getElementById('delivery-fee').textContent = `RM ${deliveryFee.toFixed(2)}`;
    document.getElementById('total').textContent = `RM ${total.toFixed(2)}`;
}

function filterCategory(category) {
    const items = document.querySelectorAll('.menu-item');
    const buttons = document.querySelectorAll('.category-filter');

    // Update button states
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });

    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    event.target.classList.add('active', 'bg-blue-600', 'text-white');

    // Filter items
    items.forEach(item => {
        if (category === 'all' || item.classList.contains(category)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Checkout function
document.getElementById('checkout-btn').addEventListener('click', function() {
    if (Object.keys(cart).length > 0) {
        // Prepare form data for checkout
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('shop_id', '{{ $shop->id }}');
        
        let itemIndex = 0;
        for (const [itemId, item] of Object.entries(cart)) {
            if (item.quantity > 0) {
                formData.append(`items[${itemIndex}][menu_item_id]`, itemId);
                formData.append(`items[${itemIndex}][quantity]`, item.quantity);
                formData.append(`items[${itemIndex}][special_requests]`, item.special_requests || '');
                itemIndex++;
            }
        }
        
        // Submit to checkout
        fetch('{{ route("orders.checkout") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .then(html => {
            // Replace current content with checkout form
            document.querySelector('main').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ralat semasa memproses checkout. Sila cuba lagi.');
        });
    }
});
</script>
@endsection