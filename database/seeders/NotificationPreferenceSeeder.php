<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NotificationPreference;

class NotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Check if user already has notification preferences
            $existingPreference = NotificationPreference::where('user_id', $user->id)->first();

            if (!$existingPreference) {
                NotificationPreference::create([
                    'user_id' => $user->id,
                    'order_status_updates' => true,
                    'order_confirmations' => true,
                    'order_deliveries' => true,
                    'promotional_emails' => false,
                    'marketing_emails' => false,
                    'email_frequency' => 'immediate',
                    'notification_channels' => ['mail'],
                ]);
            }
        }

        $this->command->info('Notification preferences created for all users.');
    }
}
