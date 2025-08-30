<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@puncakjalil.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0123456789',
                'address' => 'Puncak Jalil',
            ]
        );
        User::firstOrCreate(
            ['email' => 'vendor@puncakjalil.com'],
            [
                'name' => 'Vendor',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'phone' => '0198765432',
                'address' => 'Puncak Jalil',
            ]
        );
        User::firstOrCreate(
            ['email' => 'ahmad@puncakjalil.com'],
            [
                'name' => 'Ahmad',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '0112233445',
                'address' => 'Puncak Jalil',
            ]
        );
    }
}
