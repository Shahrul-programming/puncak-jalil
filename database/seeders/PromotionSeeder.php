<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use App\Models\Shop;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $shops = Shop::all();
        foreach ($shops as $shop) {
            Promotion::create([
                'shop_id' => $shop->id,
                'title' => 'Promosi Hebat di ' . $shop->name,
                'description' => 'Diskaun istimewa untuk komuniti!','start_date' => now(),'end_date' => now()->addDays(14),'is_featured' => rand(0,1),
            ]);
        }
    }
}
