<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestUnauthenticatedAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-unauthenticated-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test accessing shop create without authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Unauthenticated Access to Shop Create...');

        try {
            // Test accessing create page without authentication
            $this->info('Testing GET request to shops.create without auth...');

            $url = route('shops.create');
            $this->info('GET URL: ' . $url);

            // Make HTTP request without authentication
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info('Response status: ' . $response->status());

            if ($response->status() === 200) {
                $this->info('✅ Create page accessible without authentication');
            } elseif ($response->status() === 302) {
                $this->info('✅ Redirected (probably to login): ' . $response->header('Location'));
            } else {
                $this->error('❌ Unexpected response: ' . $response->status());
            }

            // Test form submission without authentication
            $this->info('Testing POST to shops.store without auth...');

            $storeUrl = route('shops.store');
            $data = [
                'name' => 'Unauth Test Shop ' . now()->timestamp,
                'category' => 'food',
                'address' => 'Test Address',
                'status' => 'active',
                '_token' => 'invalid-token'
            ];

            $response = \Illuminate\Support\Facades\Http::post($storeUrl, $data);

            $this->info('POST response status: ' . $response->status());

            if ($response->status() === 419) {
                $this->info('✅ CSRF token validation working (expected 419)');
            } elseif ($response->status() === 302) {
                $this->info('✅ Redirected to login: ' . $response->header('Location'));
            } else {
                $this->error('❌ Unexpected POST response: ' . $response->status());
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        $this->info('Test completed.');
    }
}
