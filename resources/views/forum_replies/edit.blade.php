@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Edit Forum Reply</h2>
<form method="POST" action="{{ route('forum-replies.update', $forumReply->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label>Post</label>
        <input type="text" name="post_id" class="w-full border rounded px-3 py-2" value="{{ $forumReply->post_id }}">
    </div>
    <div class="mb-4">
        <label>Kandungan</label>
        <textarea name="content" class="w-full border rounded px-3 py-2">{{ $forumReply->content }}</textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kemaskini</button>
</form>
@endsection
