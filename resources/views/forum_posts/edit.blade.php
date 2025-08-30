@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Edit Forum Post</h2>
<form method="POST" action="{{ route('forum-posts.update', $forumPost->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label>Tajuk</label>
        <input type="text" name="title" class="w-full border rounded px-3 py-2" value="{{ $forumPost->title }}">
    </div>
    <div class="mb-4">
        <label>Kandungan</label>
        <textarea name="content" class="w-full border rounded px-3 py-2">{{ $forumPost->content }}</textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kemaskini</button>
</form>
@endsection
