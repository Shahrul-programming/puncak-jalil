@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Senarai Promosi</h2>
<a href="{{ route('promotions.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Promosi</a>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Kedai</th>
            <th>Tajuk</th>
            <th>Tarikh</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promotions as $promotion)
        <tr>
            <td>{{ $promotion->shop->name ?? '-' }}</td>
            <td>{{ $promotion->title }}</td>
            <td>{{ $promotion->start_date }} - {{ $promotion->end_date }}</td>
            <td>
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || ($promotion->shop && $promotion->shop->user_id === $user->id)))
                <a href="{{ route('promotions.edit', $promotion->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam promosi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">Padam</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
