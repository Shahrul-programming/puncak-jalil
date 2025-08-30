@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Senarai Forum Post</h2>
<a href="{{ route('forum-posts.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Post</a>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Tajuk</th>
            <th>User</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($forumPosts as $forumPost)
        <tr>
            <td>{{ $forumPost->title }}</td>
            <td>{{ $forumPost->user->name ?? '-' }}</td>
            <td>
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || $forumPost->user_id === $user->id))
                <a href="{{ route('forum-posts.edit', $forumPost->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                <form action="{{ route('forum-posts.destroy', $forumPost->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Padam post ini?')">
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
