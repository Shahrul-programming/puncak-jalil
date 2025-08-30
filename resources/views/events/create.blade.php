@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Tambah Event</h2>
<form method="POST" action="{{ route('events.store') }}">
    @csrf
    <div class="mb-4">
        <label>Tajuk</label>
        <input type="text" name="title" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <div class="mb-4">
        <label>Tarikh</label>
        <input type="date" name="date" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Lokasi</label>
        <input type="text" name="location" class="w-full border rounded px-3 py-2">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
