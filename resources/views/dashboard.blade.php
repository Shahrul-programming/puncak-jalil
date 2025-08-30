@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Selamat datang, {{ $user->name }}!</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="text-lg font-semibold">Kedai Anda</div>
            <div class="text-3xl mt-2">{{ $shopCount }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="text-lg font-semibold">Review Anda</div>
            <div class="text-3xl mt-2">{{ $reviewCount }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="text-lg font-semibold">Event Anda</div>
            <div class="text-3xl mt-2">{{ $eventCount }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="text-lg font-semibold">Forum Post</div>
            <div class="text-3xl mt-2">{{ $forumCount }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="text-lg font-semibold">Laporan Anda</div>
            <div class="text-3xl mt-2">{{ $reportCount }}</div>
        </div>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold mb-2">Pautan Pantas</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('shops.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Senarai Kedai</a>
            <a href="{{ route('promotions.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Promosi</a>
            <a href="{{ route('events.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Event</a>
            <a href="{{ route('forum-posts.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Forum</a>
            <a href="{{ route('reports.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Laporan</a>
        </div>
    </div>
</div>
@endsection
