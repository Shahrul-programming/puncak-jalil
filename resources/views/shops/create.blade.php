@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Tambah Kedai</h2>
    <form method="POST" action="{{ route('shops.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Nama Kedai</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Kategori</label>
            <input type="text" name="category" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Alamat</label>
            <input type="text" name="address" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="active">Aktif</option>
                <option value="pending">Menunggu</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
