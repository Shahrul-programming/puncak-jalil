<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class TestVendorAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-vendor-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test vendor dashboard access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Vendor Dashboard Access...');

        // Find vendor user
        $vendor = User::where('role', 'vendor')->first();
        if (!$vendor) {
            $this->error('No vendor user found!');
            return;
        }

        $this->info('Found vendor: ' . $vendor->name);

        // Test middleware by simulating request
        Auth::login($vendor);
        $this->info('Logged in as vendor');

        try {
            // Test route access
            $url = route('vendor.dashboard');
            $this->info('Dashboard URL: ' . $url);

            // Make internal request
            $request = \Illuminate\Http\Request::create($url, 'GET');
            $response = app()->handle($request);

            $this->info('Response status: ' . $response->getStatusCode());

            if ($response->getStatusCode() === 200) {
                $this->info('✅ Vendor dashboard accessible!');
            } else {
                $this->error('❌ Dashboard returned status: ' . $response->getStatusCode());
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        $this->info('Test completed.');
    }
}
