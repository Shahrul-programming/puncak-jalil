<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestFormSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-form-submission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test form submission to shops.store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Form Submission to shops.store...');

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
            // Test form submission via HTTP
            $this->info('Testing HTTP POST to shops.store...');

            $data = [
                'name' => 'HTTP Test Shop ' . now()->timestamp,
                'category' => 'food',
                'description' => 'Test shop via HTTP',
                'address' => 'Test Address',
                'phone' => '0123456789',
                'status' => 'active',
                '_token' => csrf_token()
            ];

            $url = route('shops.store');
            $this->info('POST URL: ' . $url);

            // Make HTTP request
            $response = \Illuminate\Support\Facades\Http::post($url, $data);

            $this->info('Response status: ' . $response->status());
            $this->info('Response body: ' . $response->body());

            // Check if shop was created
            $shop = \App\Models\Shop::where('name', $data['name'])->first();
            if ($shop) {
                $this->info('✅ Shop created via HTTP: ' . $shop->name);
            } else {
                $this->error('❌ Shop was not created via HTTP');
            }

        } catch (\Exception $e) {
            $this->error('Error during HTTP submission: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }

        $this->info('Test completed.');
    }
}
