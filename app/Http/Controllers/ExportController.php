<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export statistics to CSV
     */
    public function exportCSV(Request $request)
    {
        $query = Statistic::where('user_id', auth()->id())
            ->orderBy('game_date', 'desc');

        // Apply filters
        if ($request->sport_type && $request->sport_type !== 'all') {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('game_date', [$request->start_date, $request->end_date]);
        }

        $statistics = $query->get();

        $filename = 'statistics_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($statistics) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Date', 'Sport', 'Minutes', 
                'Points/Goals/Spikes', 'Assists', 'Rebounds/Passes/Blocks',
                'Steals/Shots/Serves', 'Blocks/Tackles/Digs', 'Aces',
                'Notes'
            ]);

            // Data rows
            foreach ($statistics as $stat) {
                $row = [
                    $stat->game_date->format('Y-m-d'),
                    $stat->sport_type,
                    $stat->minutes_played ?? '',
                ];

                if ($stat->sport_type === 'Basketball') {
                    $row[] = $stat->points ?? '';
                    $row[] = $stat->assists ?? '';
                    $row[] = $stat->rebounds ?? '';
                    $row[] = $stat->steals ?? '';
                    $row[] = $stat->blocks ?? '';
                    $row[] = '';
                } elseif ($stat->sport_type === 'Football') {
                    $row[] = $stat->goals ?? '';
                    $row[] = $stat->assists_football ?? '';
                    $row[] = $stat->passes ?? '';
                    $row[] = $stat->shots ?? '';
                    $row[] = $stat->tackles ?? '';
                    $row[] = '';
                } else {
                    $row[] = $stat->spikes ?? '';
                    $row[] = '';
                    $row[] = $stat->blocks_volleyball ?? '';
                    $row[] = $stat->serves ?? '';
                    $row[] = $stat->digs ?? '';
                    $row[] = $stat->aces ?? '';
                }

                $row[] = $stat->notes ?? '';

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export statistics summary as JSON
     */
    public function exportJSON(Request $request)
    {
        $query = Statistic::where('user_id', auth()->id())
            ->orderBy('game_date', 'desc');

        if ($request->sport_type && $request->sport_type !== 'all') {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('game_date', [$request->start_date, $request->end_date]);
        }

        $statistics = $query->get();

        $filename = 'statistics_' . date('Y-m-d') . '.json';

        return Response::json($statistics, 200, [
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }

    /**
     * Show export options page
     */
    public function index()
    {
        $totalGames = Statistic::where('user_id', auth()->id())->count();
        
        $gamesBySport = Statistic::where('user_id', auth()->id())
            ->select('sport_type')
            ->selectRaw('count(*) as count')
            ->groupBy('sport_type')
            ->get();

        return view('exports.index', [
            'totalGames' => $totalGames,
            'gamesBySport' => $gamesBySport
        ]);
    }
}
