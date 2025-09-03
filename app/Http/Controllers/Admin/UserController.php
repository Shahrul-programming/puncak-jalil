<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Shop;
use App\Models\Event;
use App\Models\ForumPost;
use App\Models\Report;
use App\Models\Promotion;
use App\Models\Review;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');
        
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->when($status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->whereNotNull('email_verified_at');
                } elseif ($status === 'inactive') {
                    return $query->whereNull('email_verified_at');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $roles = ['user', 'vendor', 'admin'];
        $statuses = ['active', 'inactive'];
        
        // Statistics
        $totalUsers = User::count();
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        $inactiveUsers = User::whereNull('email_verified_at')->count();
        $adminCount = User::where('role', 'admin')->count();
        $vendorCount = User::where('role', 'vendor')->count();
        $userCount = User::where('role', 'user')->count();

        return view('admin.users.index', compact(
            'users', 'roles', 'statuses', 'search', 'role', 'status',
            'totalUsers', 'activeUsers', 'inactiveUsers', 'adminCount', 'vendorCount', 'userCount'
        ));
    }

    public function show(User $user)
    {
        // Load user's activities and statistics
        $userShops = Shop::where('user_id', $user->id)->count();
        $userEvents = Event::where('user_id', $user->id)->count();
        $userForumPosts = ForumPost::where('user_id', $user->id)->count();
        $userReports = Report::where('user_id', $user->id)->count();
        
        // Get promotions count (assuming promotions belong to shops)
        $userPromotions = Promotion::whereHas('shop', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        // Get reviews count (reviews by this user)
        $userReviews = Review::where('user_id', $user->id)->count();
        
        // Create user stats array for the view
        $userStats = [
            'shops' => $userShops,
            'events' => $userEvents,
            'forum_posts' => $userForumPosts,
            'promotions' => $userPromotions,
            'reviews' => $userReviews
        ];
        
        $recentShops = Shop::where('user_id', $user->id)->latest()->limit(5)->get();
        $recentEvents = Event::where('user_id', $user->id)->latest()->limit(5)->get();
        $recentForumPosts = ForumPost::where('user_id', $user->id)->latest()->limit(5)->get();
        $recentReports = Report::where('user_id', $user->id)->latest()->limit(5)->get();
        $recentReviews = Review::where('user_id', $user->id)->with('shop')->latest()->limit(5)->get();

        return view('admin.users.show', compact(
            'user', 'userShops', 'userEvents', 'userForumPosts', 'userReports',
            'recentShops', 'recentEvents', 'recentForumPosts', 'recentReports', 'recentReviews',
            'userStats'
        ));
    }

    public function create()
    {
        $roles = ['user', 'vendor', 'admin'];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,vendor,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email_verified' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $roles = ['user', 'vendor', 'admin'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:user,vendor,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
            'email_verified' => 'nullable|boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle email verification checkbox
        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        } else {
            $userData['email_verified_at'] = null;
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin user!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = 'User deactivated successfully!';
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = 'User activated successfully!';
        }

        return back()->with('success', $message);
    }

    public function impersonate(User $user)
    {
        // Prevent impersonating admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot impersonate admin users!');
        }

        // Store current admin user in session
        session(['impersonate_admin' => Auth::id()]);
        
        // Login as the target user
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('info', "You are now impersonating {$user->name}. Click 'Stop Impersonating' to return to your admin account.");
    }

    public function stopImpersonating()
    {
        if (!session('impersonate_admin')) {
            return redirect()->route('dashboard');
        }

        $adminId = session('impersonate_admin');
        session()->forget('impersonate_admin');
        
        $admin = User::find($adminId);
        Auth::login($admin);

        return redirect()->route('admin.users.index')
            ->with('success', 'Stopped impersonating user.');
    }
}
