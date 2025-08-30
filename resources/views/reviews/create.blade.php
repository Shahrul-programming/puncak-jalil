@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Tambah Review</h2>
<form method="POST" action="{{ route('reviews.store') }}">
    @csrf
    <div class="mb-4">
        <label>Kedai</label>
        <input type="text" name="shop_id" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Rating</label>
        <input type="number" name="rating" min="1" max="5" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Komen</label>
        <textarea name="comment" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
