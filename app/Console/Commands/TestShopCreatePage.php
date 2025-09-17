<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestShopCreatePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-shop-create-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test accessing shop create page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Shop Create Page Access...');

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

        try {
            // Test accessing create page
            $this->info('Testing GET request to shops.create...');

            $url = route('shops.create');
            $this->info('GET URL: ' . $url);

            // Make HTTP request
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info('Response status: ' . $response->status());

            if ($response->status() === 200) {
                $this->info('✅ Create page accessible');
                // Check if CSRF token is present
                if (strpos($response->body(), '_token') !== false) {
                    $this->info('✅ CSRF token found in response');
                } else {
                    $this->error('❌ CSRF token not found in response');
                }
            } else {
                $this->error('❌ Create page not accessible: ' . $response->status());
                $this->error('Response: ' . substr($response->body(), 0, 200));
            }

        } catch (\Exception $e) {
            $this->error('Error accessing create page: ' . $e->getMessage());
        }

        $this->info('Test completed.');
    }
}
