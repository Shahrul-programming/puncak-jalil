<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestShopFormSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-shop-form-submission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test shop form submission with complete data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Shop Form Submission...');

        // Find vendor user
        $vendor = \App\Models\User::where('role', 'vendor')->first();
        if (!$vendor) {
            $this->error('No vendor user found!');
            return;
        }

        $this->info('Found vendor: ' . $vendor->name);

        try {
            // Login as vendor
            \Illuminate\Support\Facades\Auth::login($vendor);
            $this->info('Logged in as vendor');

            // Test form submission with complete data
            $this->info('Testing form submission with complete data...');

            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => 'Complete Test Shop ' . now()->timestamp,
                'category' => 'Makanan',
                'description' => 'This is a complete test shop description',
                'address' => '123 Test Street, Test City',
                'phone' => '0123456789',
                'whatsapp' => '0123456789',
                'website' => 'https://testshop.com',
                'opening_hours' => '9 AM - 9 PM',
                'latitude' => '3.14159',
                'longitude' => '101.68685',
                'status' => 'active'
            ]);

            // Validate the request
            $this->info('Validating request data...');
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'description' => 'nullable|string|max:1000',
                'address' => 'required|string|max:500',
                'phone' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'website' => 'nullable|url|max:255',
                'opening_hours' => 'nullable|string|max:500',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'status' => 'required|in:active,inactive'
            ]);

            $this->info('✅ Validation passed');

            // Create controller and call store method
            $controller = new \App\Http\Controllers\ShopController();
            $response = $controller->store($request);

            $this->info('Store method executed');

            // Check response type
            $this->info('Response type: ' . get_class($response));

            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                $this->info('✅ Redirect response received');
                $this->info('Redirect URL: ' . $response->getTargetUrl());

                // Check if shop was created
                $shop = \App\Models\Shop::where('name', $request->name)->first();
                if ($shop) {
                    $this->info('✅ Shop created successfully: ' . $shop->name);
                    $this->info('Shop ID: ' . $shop->id);
                } else {
                    $this->error('❌ Shop was not created in database');
                }
            } else {
                $this->error('❌ Unexpected response type: ' . get_class($response));
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('❌ Validation failed:');
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->error("  {$field}: {$message}");
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }

        $this->info('Test completed.');
    }
}
