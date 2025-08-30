@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Edit Promosi</h2>
<form method="POST" action="{{ route('promotions.update', $promotion->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label>Kedai</label>
        <input type="text" name="shop_id" class="w-full border rounded px-3 py-2" value="{{ $promotion->shop_id }}">
    </div>
    <div class="mb-4">
        <label>Tajuk</label>
        <input type="text" name="title" class="w-full border rounded px-3 py-2" value="{{ $promotion->title }}">
    </div>
    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="w-full border rounded px-3 py-2">{{ $promotion->description }}</textarea>
    </div>
    <div class="mb-4">
        <label>Tarikh Mula</label>
        <input type="date" name="start_date" class="w-full border rounded px-3 py-2" value="{{ $promotion->start_date }}">
    </div>
    <div class="mb-4">
        <label>Tarikh Tamat</label>
        <input type="date" name="end_date" class="w-full border rounded px-3 py-2" value="{{ $promotion->end_date }}">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kemaskini</button>
</form>
@endsection
