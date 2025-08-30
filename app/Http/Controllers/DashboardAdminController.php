<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Papar statistik ringkas, jumlah user, kedai, review, dsb.
        return view('dashboard_admin.index');
    }
}
