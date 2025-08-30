@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Senarai Laporan</h2>
<a href="{{ route('reports.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Laporan</a>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $report->category }}</td>
            <td>{{ $report->description }}</td>
            <td>{{ $report->status }}</td>
            <td>
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || $report->user_id === $user->id))
                <a href="{{ route('reports.edit', $report->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                <form action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam laporan ini?')">
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
