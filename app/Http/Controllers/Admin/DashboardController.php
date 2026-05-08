<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TokenLog;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with key metrics.
     */
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers'   => User::count(),
            'totalTokens'  => TokenLog::sum('amount'),
            'activeStaff'  => User::where('role', 'admin')->count(),
            'todayTokens'  => TokenLog::whereDate('created_at', Carbon::today())->sum('amount'),
            'recentLogs'   => TokenLog::with(['user', 'grantedBy'])
                                        ->latest()
                                        ->take(5)
                                        ->get()
        ]);
    }
}