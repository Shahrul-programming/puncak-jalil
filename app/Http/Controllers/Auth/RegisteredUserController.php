<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Get role from URL parameter or form input
        $role = $request->query('role') ?? $request->input('role', 'user');
        
        // Validate role
        if (!in_array($role, ['user', 'vendor'])) {
            $role = 'user';
        }

        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,vendor'],
        ];

        // Additional validation for vendor
        if ($role === 'vendor') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['business_type'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:20'];
        }

        $request->validate($rules);

        // Create user with role
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ];

        // Add vendor-specific fields
        if ($role === 'vendor') {
            $userData['phone'] = $request->phone;
            $userData['address'] = $request->business_name . ' - ' . $request->business_type;
        }

        $user = User::create($userData);

        // Send notification to admin for new vendor
        if ($role === 'vendor') {
            $this->notifyAdminOfNewVendor($user, $request->business_name, $request->business_type);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($role === 'vendor') {
            return redirect()->route('dashboard')->with('success', 'Selamat datang! Akaun vendor anda telah berjaya didaftar. Admin akan semak dan approve akaun anda.');
        }

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Notify admin about new vendor registration
     */
    private function notifyAdminOfNewVendor($user, $businessName, $businessType)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewVendorNotification($user, $businessName, $businessType));
        }
    }
}
