<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users->random(2) as $user) {
            Event::create([
                'user_id' => $user->id,
                'title' => 'Aktiviti Komuniti',
                'description' => 'Aktiviti bersama komuniti perumahan.',
                'date' => now()->addDays(rand(1,30)),
                'location' => 'Dewan Komuniti',
                'type' => 'event',
            ]);
        }
    }
}
