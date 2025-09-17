<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestFormWithSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-form-with-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test form submission with proper session';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Form Submission with Session...');

        // Find vendor user
        $vendor = \App\Models\User::where('role', 'vendor')->first();
        if (!$vendor) {
            $this->error('No vendor user found!');
            return;
        }

        $this->info('Found vendor: ' . $vendor->name);

        try {
            // Test direct controller call first
            $this->info('Testing direct controller call...');

            \Illuminate\Support\Facades\Auth::login($vendor);

            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => 'Direct Test Shop ' . now()->timestamp,
                'category' => 'food',
                'description' => 'Test shop direct call',
                'address' => 'Test Address',
                'phone' => '0123456789',
                'status' => 'active'
            ]);

            $controller = new \App\Http\Controllers\ShopController();
            $response = $controller->store($request);

            // Check if shop was created
            $shop = \App\Models\Shop::where('name', $request->name)->first();
            if ($shop) {
                $this->info('✅ Direct controller call successful: ' . $shop->name);
            } else {
                $this->error('❌ Direct controller call failed');
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }

        $this->info('Test completed.');
    }
}
