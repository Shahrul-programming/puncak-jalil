@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Tambah Promosi</h2>
<form method="POST" action="{{ route('promotions.store') }}">
    @csrf
    <div class="mb-4">
        <label>Kedai</label>
        <input type="text" name="shop_id" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Tajuk</label>
        <input type="text" name="title" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <div class="mb-4">
        <label>Tarikh Mula</label>
        <input type="date" name="start_date" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Tarikh Tamat</label>
        <input type="date" name="end_date" class="w-full border rounded px-3 py-2">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
