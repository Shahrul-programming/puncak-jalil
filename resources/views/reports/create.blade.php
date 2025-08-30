@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Tambah Laporan</h2>
<form method="POST" action="{{ route('reports.store') }}">
    @csrf
    <div class="mb-4">
        <label>Kategori</label>
        <input type="text" name="category" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <div class="mb-4">
        <label>Status</label>
        <select name="status" class="w-full border rounded px-3 py-2">
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="resolved">Resolved</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
