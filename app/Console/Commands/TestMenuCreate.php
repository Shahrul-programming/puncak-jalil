<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMenuCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-menu-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test menu create functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Menu Create Functionality...');

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
            // Test using internal Laravel request
            $this->info('Testing with internal Laravel request...');

            $request = new \Illuminate\Http\Request();
            $request->setMethod('GET');
            $request->server->set('REQUEST_URI', '/menu/create');

            // Create controller and call create method
            $controller = new \App\Http\Controllers\MenuController();
            $response = $controller->create($request);

            $this->info('Controller response type: ' . get_class($response));

            if ($response instanceof \Illuminate\View\View) {
                $this->info('✅ View returned successfully');
                $this->info('View name: ' . $response->getName());
                $this->info('View data keys: ' . implode(', ', array_keys($response->getData())));
            } else {
                $this->error('❌ Unexpected response type');
            }

            // Test accessing menu create page via HTTP with session
            $this->info('Testing GET request to menu.create via HTTP...');

            $url = route('menu.create');
            $this->info('GET URL: ' . $url);

            // Make HTTP request
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info('HTTP Response status: ' . $response->status());

            if ($response->status() === 200) {
                $this->info('✅ Menu create page accessible via HTTP');
            } elseif ($response->status() === 302) {
                $this->info('✅ Redirected: ' . $response->header('Location'));
            } else {
                $this->error('❌ Unexpected response: ' . $response->status());
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }

        $this->info('Test completed.');
    }
}
