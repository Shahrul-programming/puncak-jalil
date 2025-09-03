<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $status = $request->get('status');
        
        $reports = Report::with('user')
            ->search($search)
            ->category($category)
            ->status($status)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Report::getCategories();
        $statuses = Report::getStatuses();
        
        // Statistics
        $totalReports = Report::count();
        $openReports = Report::open()->count();
        $inProgressReports = Report::inProgress()->count();
        $resolvedReports = Report::resolved()->count();

        return view('reports.index', compact(
            'reports', 'categories', 'statuses', 'search', 'category', 'status',
            'totalReports', 'openReports', 'inProgressReports', 'resolvedReports'
        ));
    }

    public function create()
    {
        $categories = Report::getCategories();
        return view('reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:' . implode(',', array_keys(Report::getCategories())),
            'description' => 'required|string|min:10',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'user_id' => Auth::id(),
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'status' => 'open'
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('reports', $filename, 'public');
            $data['image'] = $path;
        }

        Report::create($data);

        return redirect()->route('reports.index')->with('success', 'Laporan berjaya dihantar!');
    }

    public function show(Report $report)
    {
        $report->load('user');
        
        // Get related reports from same category
        $relatedReports = Report::where('category', $report->category)
            ->where('id', '!=', $report->id)
            ->latest()
            ->take(5)
            ->get();

        return view('reports.show', compact('report', 'relatedReports'));
    }

    public function edit(Report $report)
    {
        $user = Auth::user();
        
        // Only admin or report owner can edit
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        // Users can only edit open reports, admin can edit any
        if ($user->role !== 'admin' && $report->status !== 'open') {
            abort(403, 'Laporan yang sudah dalam tindakan tidak boleh diedit.');
        }

        $categories = Report::getCategories();
        return view('reports.edit', compact('report', 'categories'));
    }

    public function update(Request $request, Report $report)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        if ($user->role !== 'admin' && $report->status !== 'open') {
            abort(403, 'Laporan yang sudah dalam tindakan tidak boleh diedit.');
        }

        $request->validate([
            'category' => 'required|string|in:' . implode(',', array_keys(Report::getCategories())),
            'description' => 'required|string|min:10',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($report->image) {
                Storage::disk('public')->delete($report->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('reports', $filename, 'public');
            $data['image'] = $path;
        }

        $report->update($data);

        return redirect()->route('reports.show', $report)->with('success', 'Laporan berjaya dikemaskini!');
    }

    public function destroy(Report $report)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        // Delete image if exists
        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Laporan berjaya dipadam!');
    }

    // Admin functions
    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved'
        ]);

        $report->update(['status' => $request->status]);
        
        $statusName = Report::getStatuses()[$request->status];
        return back()->with('success', "Status laporan berjaya dikemaskini kepada: {$statusName}");
    }

    // Admin dashboard for reports
    public function adminIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $search = $request->get('search');
        $category = $request->get('category');
        $status = $request->get('status');
        
        $reports = Report::with('user')
            ->search($search)
            ->category($category)
            ->status($status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Report::getCategories();
        $statuses = Report::getStatuses();
        
        // Statistics for admin
        $stats = [
            'total' => Report::count(),
            'open' => Report::open()->count(),
            'in_progress' => Report::inProgress()->count(),
            'resolved' => Report::resolved()->count(),
            'recent' => Report::recent()->count()
        ];

        return view('reports.admin-index', compact(
            'reports', 'categories', 'statuses', 'search', 'category', 'status', 'stats'
        ));
    }
}
