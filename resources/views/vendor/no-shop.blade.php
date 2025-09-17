@extends('layouts.app')

@section('title', 'Tiada Kedai')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <i class="fas fa-store text-gray-300 text-6xl mb-4"></i>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Tiada Kedai Didaftarkan
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Anda perlu mendaftarkan kedai terlebih dahulu untuk mengakses dashboard vendor.
            </p>
        </div>

        <div class="mt-8 space-y-6">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="space-y-4">
                    <a href="{{ route('shops.create') }}"
                       class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Daftar Kedai Baru
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection