
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Senarai Kedai
        </h2>
        <a href="{{ route('shops.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Tambah Kedai</a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Alamat</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $shop)
                            <tr>
                                <td class="border px-4 py-2">{{ $shop->name }}</td>
                                <td class="border px-4 py-2">{{ $shop->category }}</td>
                                <td class="border px-4 py-2">{{ $shop->address }}</td>
                                <td class="border px-4 py-2">{{ $shop->status }}</td>
                                <td class="border px-4 py-2">
                                    @php $user = auth()->user(); @endphp
                                    @if($user && ($user->role === 'admin' || $shop->user_id === $user->id))
                                        <a href="{{ route('shops.edit', $shop->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                                        <form action="{{ route('shops.destroy', $shop->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam kedai ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">Padam</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Tiada kedai dijumpai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

