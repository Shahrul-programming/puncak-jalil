@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Senarai Forum Reply</h2>
<a href="{{ route('forum-replies.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Reply</a>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Post</th>
            <th>User</th>
            <th>Kandungan</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($forumReplies as $forumReply)
        <tr>
            <td>{{ $forumReply->post->title ?? '-' }}</td>
            <td>{{ $forumReply->user->name ?? '-' }}</td>
            <td>{{ $forumReply->content }}</td>
            <td>
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || $forumReply->user_id === $user->id))
                <a href="{{ route('forum-replies.edit', $forumReply->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                <form action="{{ route('forum-replies.destroy', $forumReply->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam reply ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">Padam</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
