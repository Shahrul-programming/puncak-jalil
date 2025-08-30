@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Senarai Event</h2>
<a href="{{ route('events.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Event</a>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Tajuk</th>
            <th>Tarikh</th>
            <th>Lokasi</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{ $event->title }}</td>
            <td>{{ $event->date }}</td>
            <td>{{ $event->location }}</td>
            <td>
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || $event->user_id === $user->id))
                <a href="{{ route('events.edit', $event->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam event ini?')">
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
