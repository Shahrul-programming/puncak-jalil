@extends('layouts.app')
@section('content')
<h2 class="text-xl font-bold mb-4">Edit Laporan</h2>
<form method="POST" action="{{ route('reports.update', $report->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label>Kategori</label>
        <input type="text" name="category" class="w-full border rounded px-3 py-2" value="{{ $report->category }}">
    </div>
    <div class="mb-4">
        <label>Deskripsi</label>
        <textarea name="description" class="w-full border rounded px-3 py-2">{{ $report->description }}</textarea>
    </div>
    <div class="mb-4">
        <label>Status</label>
        <select name="status" class="w-full border rounded px-3 py-2">
            <option value="open" @if($report->status=='open') selected @endif>Open</option>
            <option value="in_progress" @if($report->status=='in_progress') selected @endif>In Progress</option>
            <option value="resolved" @if($report->status=='resolved') selected @endif>Resolved</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kemaskini</button>
</form>
@endsection
