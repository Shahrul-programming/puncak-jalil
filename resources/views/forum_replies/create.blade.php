@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Tambah Forum Reply</h2>
<form method="POST" action="{{ route('forum-replies.store') }}">
    @csrf
    <div class="mb-4">
        <label>Post</label>
        <input type="text" name="post_id" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
        <label>Kandungan</label>
        <textarea name="content" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
