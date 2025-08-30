@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Senarai Review</h2>
<a href="{{ route('reviews.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Review</a>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Kedai</th>
            <th>User</th>
            <th>Rating</th>
            <th>Komen</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reviews as $review)
        <tr>
            <td>{{ $review->shop->name ?? '-' }}</td>
            <td>{{ $review->user->name ?? '-' }}</td>
            <td>{{ $review->rating }}</td>
            <td>{{ $review->comment }}</td>
            <td>
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || $review->user_id === $user->id))
                <a href="{{ route('reviews.edit', $review->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam review ini?')">
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
