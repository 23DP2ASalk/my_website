<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'regular_users' => User::where('is_admin', false)->count(),
            'recent_users' => User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
            'active_today' => User::whereDate('last_login_at', today())->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}