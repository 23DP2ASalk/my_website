<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        $userId = auth()->id();

        // Overall statistics
        $totalGames = Statistic::where('user_id', $userId)->count();
        
        // Games per sport
        $gamesBySport = Statistic::where('user_id', $userId)
            ->select('sport_type', DB::raw('count(*) as count'))
            ->groupBy('sport_type')
            ->get();

        // Average stats by sport
        $averageStats = $this->getAverageStatsBySport($userId);

        // Performance trends (last 10 games)
        $recentTrends = $this->getRecentTrends($userId);

        // Best performances
        $bestPerformances = $this->getBestPerformances($userId);

        return view('analytics.index', [
            'totalGames' => $totalGames,
            'gamesBySport' => $gamesBySport,
            'averageStats' => $averageStats,
            'recentTrends' => $recentTrends,
            'bestPerformances' => $bestPerformances
        ]);
    }

    /**
     * Get average statistics by sport
     */
    private function getAverageStatsBySport($userId)
    {
        $sports = ['Basketball', 'Football', 'Volleyball'];
        $averages = [];

        foreach ($sports as $sport) {
            $stats = Statistic::where('user_id', $userId)
                ->where('sport_type', $sport)
                ->get();

            if ($stats->count() > 0) {
                $averages[$sport] = $this->calculateAverages($stats, $sport);
            }
        }

        return $averages;
    }

    /**
     * Calculate averages for a sport
     */
    private function calculateAverages($stats, $sport)
    {
        $count = $stats->count();
        
        if ($sport === 'Basketball') {
            return [
                'points' => round($stats->avg('points'), 1),
                'assists' => round($stats->avg('assists'), 1),
                'rebounds' => round($stats->avg('rebounds'), 1),
                'steals' => round($stats->avg('steals'), 1),
                'blocks' => round($stats->avg('blocks'), 1),
                'games' => $count
            ];
        } elseif ($sport === 'Football') {
            return [
                'goals' => round($stats->avg('goals'), 1),
                'assists' => round($stats->avg('assists_football'), 1),
                'shots' => round($stats->avg('shots'), 1),
                'passes' => round($stats->avg('passes'), 1),
                'tackles' => round($stats->avg('tackles'), 1),
                'games' => $count
            ];
        } else {
            return [
                'spikes' => round($stats->avg('spikes'), 1),
                'blocks' => round($stats->avg('blocks_volleyball'), 1),
                'serves' => round($stats->avg('serves'), 1),
                'digs' => round($stats->avg('digs'), 1),
                'aces' => round($stats->avg('aces'), 1),
                'games' => $count
            ];
        }
    }

    /**
     * Get recent performance trends
     */
    private function getRecentTrends($userId)
    {
        return Statistic::where('user_id', $userId)
            ->orderBy('game_date', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Get best performances
     */
    private function getBestPerformances($userId)
    {
        return [
            'basketball' => Statistic::where('user_id', $userId)
                ->where('sport_type', 'Basketball')
                ->orderBy('points', 'desc')
                ->first(),
            'football' => Statistic::where('user_id', $userId)
                ->where('sport_type', 'Football')
                ->orderBy('goals', 'desc')
                ->first(),
            'volleyball' => Statistic::where('user_id', $userId)
                ->where('sport_type', 'Volleyball')
                ->orderBy('spikes', 'desc')
                ->first(),
        ];
    }
}
