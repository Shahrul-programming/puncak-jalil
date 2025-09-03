<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shop;
use App\Models\Review;
use App\Models\Event;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\Report;
use App\Models\Promotion;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $userCount = User::count();
        $shopCount = Shop::count();
        $eventCount = Event::count();
        $forumPostCount = ForumPost::count();
        $reportCount = Report::count();
        $promotionCount = Promotion::count();
        
        // User Statistics
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        $activeUsersToday = User::whereDate('updated_at', Carbon::today())->count();
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
            
        // Shop Statistics
        $newShopsThisMonth = Shop::whereMonth('created_at', Carbon::now()->month)->count();
        $averageRating = Review::avg('rating');
        $totalReviews = Review::count();
        $reviewCount = $totalReviews; // Legacy compatibility
        
        // Event Statistics
        $upcomingEvents = Event::where('date', '>=', Carbon::now())->count();
        $pastEvents = Event::where('date', '<', Carbon::now())->count();
        $eventsThisMonth = Event::whereMonth('date', Carbon::now()->month)->count();
        
        // Forum Statistics
        $forumRepliesCount = ForumReply::count();
        $pinnedPosts = ForumPost::where('is_pinned', true)->count();
        $lockedPosts = ForumPost::where('is_locked', true)->count();
        $activeForumUsers = ForumPost::distinct('user_id')->count('user_id');
        
        // Reports Statistics
        $openReports = Report::where('status', 'open')->count();
        $inProgressReports = Report::where('status', 'in_progress')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $reportsByCategory = Report::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
            
        // Recent Activities
        $recentUsers = User::latest()->limit(5)->get();
        $recentShops = Shop::with('user')->latest()->limit(5)->get();
        $recentReports = Report::with('user')->latest()->limit(5)->get();
        $recentForumPosts = ForumPost::with('user')->latest()->limit(5)->get();
        
        // Monthly Growth Data (for charts)
        $monthlyUsers = User::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();
        
        $monthlyShops = Shop::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();
        
        // System Health
        $systemHealth = [
            'database_size' => $this->getDatabaseSize(),
            'total_uploads' => $this->getTotalUploads(),
            'avg_response_time' => '< 200ms', // Placeholder
            'uptime' => '99.9%' // Placeholder
        ];
        
        return view('admin.dashboard', compact(
            // Basic Stats
            'userCount', 'shopCount', 'eventCount', 'forumPostCount', 'reportCount', 'promotionCount',
            
            // User Stats
            'newUsersThisMonth', 'activeUsersToday', 'usersByRole',
            
            // Shop Stats
            'newShopsThisMonth', 'averageRating', 'totalReviews',
            
            // Event Stats
            'upcomingEvents', 'pastEvents', 'eventsThisMonth',
            
            // Forum Stats
            'forumRepliesCount', 'pinnedPosts', 'lockedPosts', 'activeForumUsers',
            
            // Report Stats
            'openReports', 'inProgressReports', 'resolvedReports', 'reportsByCategory',
            
            // Recent Activities
            'recentUsers', 'recentShops', 'recentReports', 'recentForumPosts',
            
            // Chart Data
            'monthlyUsers', 'monthlyShops',
            
            // System Health
            'systemHealth',
            
            // Legacy compatibility
            'reviewCount'
        ));
    }
    
    private function getDatabaseSize()
    {
        try {
            $size = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'size' FROM information_schema.tables WHERE table_schema = DATABASE()")[0]->size ?? 0;
            return $size . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
    
    private function getTotalUploads()
    {
        $uploadPaths = [
            storage_path('app/public/shops'),
            storage_path('app/public/events'),
            storage_path('app/public/reports'),
            storage_path('app/public/promotions')
        ];
        
        $totalFiles = 0;
        foreach ($uploadPaths as $path) {
            if (is_dir($path)) {
                $totalFiles += count(glob($path . '/*'));
            }
        }
        
        return $totalFiles;
    }
}
