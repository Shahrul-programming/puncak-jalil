<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationPreferenceController extends Controller
{
    /**
     * Display the user's notification preferences.
     */
    public function index()
    {
        $user = Auth::user();
        $preferences = $user->notificationPreference ?? NotificationPreference::getForUser($user->id);

        return view('notification-preferences.index', compact('preferences'));
    }

    /**
     * Update the user's notification preferences.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'order_status_updates' => 'boolean',
            'order_confirmations' => 'boolean',
            'order_deliveries' => 'boolean',
            'promotional_emails' => 'boolean',
            'marketing_emails' => 'boolean',
            'email_frequency' => 'required|in:immediate,daily,weekly',
            'notification_channels' => 'array',
            'notification_channels.*' => 'in:mail,sms,push',
        ]);

        $preferences = NotificationPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'order_status_updates' => $request->boolean('order_status_updates', true),
                'order_confirmations' => $request->boolean('order_confirmations', true),
                'order_deliveries' => $request->boolean('order_deliveries', true),
                'promotional_emails' => $request->boolean('promotional_emails', false),
                'marketing_emails' => $request->boolean('marketing_emails', false),
                'email_frequency' => $request->email_frequency,
                'notification_channels' => $request->notification_channels ?? ['mail'],
            ]
        );

        return redirect()->back()->with('success', 'Tetapan notifikasi berjaya dikemaskini.');
    }
}
