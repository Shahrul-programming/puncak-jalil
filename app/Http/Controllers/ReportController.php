<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::all();
        return view('reports.index', compact('reports'));
    }
    public function create()
    {
        return view('reports.create');
    }
    public function store(Request $request)
    {
        // Simpan report baru
    }
    public function edit($id)
    {
        $report = Report::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('reports.edit', compact('report'));
    }
    public function update(Request $request, $id)
    {
        // Kemaskini report
    }
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Laporan dipadam.');
    }
}
