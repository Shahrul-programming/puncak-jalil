<x-mail::message>
# Kemaskini Status Pesanan

Salam {{ $order->user->name }},

**{{ $message }}**

## Maklumat Pesanan
- **Nombor Pesanan:** {{ $order->order_number }}
- **Restoran:** {{ $order->shop->name }}
- **Status Semasa:** {{ $statusText }}
- **Jumlah:** RM{{ number_format($order->total_amount, 2) }}
- **Alamat Penghantaran:** {{ $order->delivery_address }}

@if($order->rider)
- **Rider:** {{ $order->rider->name }}
@endif

@if($order->special_instructions)
## Arahan Khas
{{ $order->special_instructions }}
@endif

<x-mail::button :url="url('/orders/' . $order->id)" color="primary">
Lihat Pesanan
</x-mail::button>

Terima kasih kerana menggunakan Puncak Jalil Food Delivery!

Salam mesra,<br>
**Puncak Jalil Food Delivery Team**
</x-mail::message>
