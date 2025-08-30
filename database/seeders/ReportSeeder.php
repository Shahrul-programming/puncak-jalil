<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users->random(2) as $user) {
            Report::create([
                'user_id' => $user->id,
                'category' => 'Kemudahan',
                'description' => 'Lampu jalan rosak di kawasan taman.',
                'location' => 'Jalan Mawar 1',
                'image' => null,
                'status' => 'open',
            ]);
        }
    }
}
