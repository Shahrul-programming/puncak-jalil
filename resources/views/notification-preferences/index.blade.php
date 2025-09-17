@extends('layouts.app')

@section('title', 'Tetapan Notifikasi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <i class="fas fa-bell text-2xl text-blue-600 mr-3"></i>
                <h1 class="text-2xl font-bold text-gray-800">Tetapan Notifikasi</h1>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('notification-preferences.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Order Status Updates -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Kemaskini Status Pesanan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="order_status_updates" name="order_status_updates" value="1"
                                   {{ old('order_status_updates', $preferences->order_status_updates) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="order_status_updates" class="ml-2 text-gray-700">
                                Terima notifikasi apabila status pesanan dikemaskini
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="order_confirmations" name="order_confirmations" value="1"
                                   {{ old('order_confirmations', $preferences->order_confirmations) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="order_confirmations" class="ml-2 text-gray-700">
                                Terima notifikasi pengesahan pesanan
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="order_deliveries" name="order_deliveries" value="1"
                                   {{ old('order_deliveries', $preferences->order_deliveries) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="order_deliveries" class="ml-2 text-gray-700">
                                Terima notifikasi penghantaran pesanan
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Marketing Preferences -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">E-mel Pemasaran</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="promotional_emails" name="promotional_emails" value="1"
                                   {{ old('promotional_emails', $preferences->promotional_emails) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="promotional_emails" class="ml-2 text-gray-700">
                                Terima e-mel promosi dan tawaran khas
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="marketing_emails" name="marketing_emails" value="1"
                                   {{ old('marketing_emails', $preferences->marketing_emails) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="marketing_emails" class="ml-2 text-gray-700">
                                Terima e-mel pemasaran dan kemaskini produk
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Email Frequency -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Kekerapan E-mel</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="radio" id="immediate" name="email_frequency" value="immediate"
                                   {{ old('email_frequency', $preferences->email_frequency) === 'immediate' ? 'checked' : '' }}
                                   class="border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="immediate" class="ml-2 text-gray-700">
                                Segera - Terima notifikasi dengan segera
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="daily" name="email_frequency" value="daily"
                                   {{ old('email_frequency', $preferences->email_frequency) === 'daily' ? 'checked' : '' }}
                                   class="border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="daily" class="ml-2 text-gray-700">
                                Harian - Ringkasan harian notifikasi
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="weekly" name="email_frequency" value="weekly"
                                   {{ old('email_frequency', $preferences->email_frequency) === 'weekly' ? 'checked' : '' }}
                                   class="border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="weekly" class="ml-2 text-gray-700">
                                Mingguan - Ringkasan mingguan notifikasi
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notification Channels -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Saluran Notifikasi</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="channel_mail" name="notification_channels[]" value="mail"
                                   {{ in_array('mail', old('notification_channels', $preferences->notification_channels ?? ['mail'])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="channel_mail" class="ml-2 text-gray-700">
                                E-mel
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="channel_sms" name="notification_channels[]" value="sms"
                                   {{ in_array('sms', old('notification_channels', $preferences->notification_channels ?? [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="channel_sms" class="ml-2 text-gray-700">
                                SMS (Akan datang)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="channel_push" name="notification_channels[]" value="push"
                                   {{ in_array('push', old('notification_channels', $preferences->notification_channels ?? [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="channel_push" class="ml-2 text-gray-700">
                                Push Notification (Akan datang)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Tetapan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection