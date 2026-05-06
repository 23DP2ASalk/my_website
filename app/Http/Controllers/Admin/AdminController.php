<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_statistics' => Statistic::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'statistics_by_sport' => Statistic::select('sport_type', DB::raw('count(*) as count'))
                ->groupBy('sport_type')
                ->get(),
            'users_with_stats' => User::withCount('statistics')
                ->orderBy('statistics_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.dashboard', $stats);
    }

    /**
     * Display all users
     */
    public function users()
    {
        $users = User::withCount('statistics')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()->back()->with('success', 'Admin status updated successfully!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
