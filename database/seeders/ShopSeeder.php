<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        Shop::create([
            'user_id' => 2,
            'name' => 'Kedai Runcit Maju',
            'category' => 'Runcit',
            'description' => 'Menjual barangan keperluan harian',
            'phone' => '0198765432',
            'whatsapp_link' => 'https://wa.me/60198765432',
            'address' => 'Jalan 1, Puncak Jalil',
            'latitude' => '3.0333',
            'longitude' => '101.6333',
            'opening_hours' => '8:00-22:00',
            'status' => 'active',
        ]);
        Shop::create([
            'user_id' => 2,
            'name' => 'Servis Aircond Pak Mat',
            'category' => 'Servis',
            'description' => 'Servis dan baiki aircond rumah',
            'phone' => '0191234567',
            'whatsapp_link' => 'https://wa.me/60191234567',
            'address' => 'Jalan 2, Puncak Jalil',
            'latitude' => '3.0340',
            'longitude' => '101.6340',
            'opening_hours' => '9:00-18:00',
            'status' => 'active',
        ]);
    }
}
