<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Http\Controllers\ShopController;

class TestShopCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-shop-creation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test shop creation process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Shop Creation Process...');

        // Find vendor user
        $vendor = \App\Models\User::where('role', 'vendor')->first();
        if (!$vendor) {
            $this->error('No vendor user found!');
            return;
        }

        $this->info('Found vendor: ' . $vendor->name);

        // Login as vendor
        \Illuminate\Support\Facades\Auth::login($vendor);
        $this->info('Logged in as vendor');

        // Test shop creation
        try {
            $this->info('Testing shop creation...');

            // Create a request with test data
            $requestData = [
                'name' => 'Test Shop ' . now()->timestamp,
                'category' => 'food',
                'description' => 'Test shop description',
                'address' => 'Test Address',
                'phone' => '0123456789',
                'status' => 'active'
            ];

            $request = new \Illuminate\Http\Request();
            $request->merge($requestData);

            // Create controller instance
            $controller = new \App\Http\Controllers\ShopController();

            // Call store method
            $response = $controller->store($request);

            $this->info('Store method called successfully');

            // Check if shop was created
            $shop = \App\Models\Shop::where('name', $requestData['name'])->first();
            if ($shop) {
                $this->info('✅ Shop created successfully: ' . $shop->name);
                $this->info('Shop ID: ' . $shop->id);
                $this->info('Shop User ID: ' . $shop->user_id);
            } else {
                $this->error('❌ Shop was not created in database');
            }

        } catch (\Exception $e) {
            $this->error('Error during shop creation: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }

        $this->info('Test completed.');
    }
}
