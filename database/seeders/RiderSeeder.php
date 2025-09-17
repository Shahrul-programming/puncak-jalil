<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rider;

class RiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $riders = [
            [
                'name' => 'Ahmad bin Abdullah',
                'phone' => '0123456789',
                'email' => 'ahmad.rider@puncakjalil.com',
                'ic_number' => '850101-01-1234',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'ABC1234',
                'status' => 'active',
                'availability' => 'available',
                'rating' => 4.8,
                'total_deliveries' => 156,
                'address' => 'No. 12, Jalan Puncak 1, Taman Puncak Jalil, 43300 Seri Kembangan, Selangor',
                'latitude' => 3.0567,
                'longitude' => 101.7101,
            ],
            [
                'name' => 'Siti binti Hassan',
                'phone' => '0167890123',
                'email' => 'siti.rider@puncakjalil.com',
                'ic_number' => '880512-02-5678',
                'vehicle_type' => 'car',
                'vehicle_number' => 'WXY5678',
                'status' => 'active',
                'availability' => 'available',
                'rating' => 4.9,
                'total_deliveries' => 203,
                'address' => 'Lot 45, Jalan Puncak Perdana, Taman Puncak Jalil, 43300 Seri Kembangan, Selangor',
                'latitude' => 3.0589,
                'longitude' => 101.7123,
            ],
            [
                'name' => 'Mohd Razali bin Ismail',
                'phone' => '0198765432',
                'email' => 'razali.rider@puncakjalil.com',
                'ic_number' => '820315-03-9012',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'DEF9012',
                'status' => 'active',
                'availability' => 'busy',
                'rating' => 4.6,
                'total_deliveries' => 89,
                'address' => 'Block A-5-2, Apartment Puncak View, Jalan Puncak 3, 43300 Seri Kembangan, Selangor',
                'latitude' => 3.0545,
                'longitude' => 101.7089,
            ],
            [
                'name' => 'Nur Aisyah binti Abdullah',
                'phone' => '0145678901',
                'email' => 'aisyah.rider@puncakjalil.com',
                'ic_number' => '900728-04-3456',
                'vehicle_type' => 'bicycle',
                'vehicle_number' => 'N/A',
                'status' => 'active',
                'availability' => 'available',
                'rating' => 4.7,
                'total_deliveries' => 67,
                'address' => 'No. 8, Jalan Puncak Murni, Taman Puncak Jalil, 43300 Seri Kembangan, Selangor',
                'latitude' => 3.0578,
                'longitude' => 101.7098,
            ],
            [
                'name' => 'Hassan bin Yusof',
                'phone' => '0176543210',
                'email' => 'hassan.rider@puncakjalil.com',
                'ic_number' => '800422-05-7890',
                'vehicle_type' => 'car',
                'vehicle_number' => 'JKL3456',
                'status' => 'active',
                'availability' => 'available',
                'rating' => 4.5,
                'total_deliveries' => 134,
                'address' => 'No. 25, Jalan Puncak Indah, Taman Puncak Jalil, 43300 Seri Kembangan, Selangor',
                'latitude' => 3.0592,
                'longitude' => 101.7115,
            ],
            [
                'name' => 'Fatimah binti Omar',
                'phone' => '0132109876',
                'email' => 'fatimah.rider@puncakjalil.com',
                'ic_number' => '860930-06-1122',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'MNO7890',
                'status' => 'inactive',
                'availability' => 'offline',
                'rating' => 4.3,
                'total_deliveries' => 45,
                'address' => 'No. 15, Jalan Puncak Damai, Taman Puncak Jalil, 43300 Seri Kembangan, Selangor',
                'latitude' => 3.0556,
                'longitude' => 101.7108,
            ],
        ];

        foreach ($riders as $riderData) {
            Rider::create($riderData);
        }
    }
}
