<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestVendorRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-vendor-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test vendor routes functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Vendor Routes...');

        // Test 1: Check if vendor routes exist
        $vendorRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return str_contains($route->getName(), 'vendor.');
        });

        $this->info('Found ' . $vendorRoutes->count() . ' vendor routes:');
        foreach ($vendorRoutes as $route) {
            $this->line('  - ' . $route->getName() . ' (' . $route->methods()[0] . ' ' . $route->uri() . ')');
        }

        // Test 2: Check vendor user exists
        $vendor = User::where('role', 'vendor')->first();
        if ($vendor) {
            $this->info('Vendor user found: ' . $vendor->name);

            // Test 3: Try to authenticate as vendor
            Auth::login($vendor);
            $this->info('Authenticated as vendor: ' . Auth::user()->name);

            // Test 4: Try to access vendor dashboard route
            try {
                $url = route('vendor.dashboard');
                $this->info('Vendor dashboard URL: ' . $url);

                // Test route resolution
                $route = Route::getRoutes()->getByName('vendor.dashboard');
                if ($route) {
                    $this->info('Route found with middleware: ' . implode(', ', $route->middleware()));
                }

            } catch (\Exception $e) {
                $this->error('Error accessing vendor dashboard: ' . $e->getMessage());
            }

        } else {
            $this->error('No vendor user found!');
        }

        $this->info('Test completed.');
    }
}
