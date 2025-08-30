<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $shops = Shop::all();
        foreach ($shops as $shop) {
            foreach ($users->random(2) as $user) {
                Review::create([
                    'shop_id' => $shop->id,
                    'user_id' => $user->id,
                    'rating' => rand(3,5),
                    'comment' => 'Contoh review untuk kedai ' . $shop->name,
                ]);
            }
        }
    }
}
