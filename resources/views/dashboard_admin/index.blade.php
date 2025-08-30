@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Dashboard Admin</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <div class="text-lg font-semibold">Jumlah Pengguna</div>
        <div class="text-3xl mt-2">{{ $userCount ?? '-' }}</div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <div class="text-lg font-semibold">Jumlah Kedai</div>
        <div class="text-3xl mt-2">{{ $shopCount ?? '-' }}</div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <div class="text-lg font-semibold">Jumlah Laporan</div>
        <div class="text-3xl mt-2">{{ $reportCount ?? '-' }}</div>
    </div>
</div>
@endsection
