<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestShopIndexAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-shop-index-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test access to shops index page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Shop Index Access...');

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
            // Test accessing shops index
            $this->info('Testing GET request to shops.index...');

            $url = route('shops.index');
            $this->info('GET URL: ' . $url);

            // Make HTTP request
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info('Response status: ' . $response->status());

            if ($response->status() === 200) {
                $this->info('✅ Shops index accessible');

                // Check if vendor's shops are displayed
                $body = $response->body();
                $vendorShops = \App\Models\Shop::where('user_id', $vendor->id)->get();

                foreach ($vendorShops as $shop) {
                    if (strpos($body, $shop->name) !== false) {
                        $this->info('✅ Shop found in response: ' . $shop->name);
                    } else {
                        $this->info('❌ Shop not found in response: ' . $shop->name);
                    }
                }

            } elseif ($response->status() === 302) {
                $this->info('✅ Redirected: ' . $response->header('Location'));
            } else {
                $this->error('❌ Unexpected response: ' . $response->status());
                $this->error('Response: ' . substr($response->body(), 0, 200));
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        $this->info('Test completed.');
    }
}
