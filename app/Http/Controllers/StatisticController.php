<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $query = Statistic::where('user_id', auth()->id())
            ->orderBy('game_date', 'desc');

        // Filters...
        if ($request->sport_type && $request->sport_type !== 'all') {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('game_date', [$request->start_date, $request->end_date]);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('notes', 'like', "%{$request->search}%")
                ->orWhere('sport_type', 'like', "%{$request->search}%");
            });
        }

        $statistics = $query->paginate(10)->withQueryString();

        return view('statistics.index', [
            'statistics' => $statistics,
            'filters' => $request->only(['sport_type', 'start_date', 'end_date', 'search'])
        ]);
    }

    public function create()
    {
        return view('statistics.create', [
            'sportTypes' => ['Basketball', 'Football', 'Volleyball']
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sport_type' => 'required|in:Basketball,Football,Volleyball',
            'game_date' => 'required|date',
            'minutes_played' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'points' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'rebounds' => 'nullable|integer|min:0',
            'steals' => 'nullable|integer|min:0',
            'blocks' => 'nullable|integer|min:0',
            'goals' => 'nullable|integer|min:0',
            'assists_football' => 'nullable|integer|min:0',
            'shots' => 'nullable|integer|min:0',
            'passes' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'spikes' => 'nullable|integer|min:0',
            'blocks_volleyball' => 'nullable|integer|min:0',
            'serves' => 'nullable|integer|min:0',
            'digs' => 'nullable|integer|min:0',
            'aces' => 'nullable|integer|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        Statistic::create($validated);

        return redirect()->route('statistics.index')
            ->with('success', 'Statistics added successfully!');
    }

    public function edit(Statistic $statistic)
    {
        if ($statistic->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('statistics.edit', [
            'statistic' => $statistic,
            'sportTypes' => ['Basketball', 'Football', 'Volleyball']
        ]);
    }

    public function update(Request $request, Statistic $statistic)
    {
        if ($statistic->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'sport_type' => 'required|in:Basketball,Football,Volleyball',
            'game_date' => 'required|date',
            'minutes_played' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'points' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'rebounds' => 'nullable|integer|min:0',
            'steals' => 'nullable|integer|min:0',
            'blocks' => 'nullable|integer|min:0',
            'goals' => 'nullable|integer|min:0',
            'assists_football' => 'nullable|integer|min:0',
            'shots' => 'nullable|integer|min:0',
            'passes' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'spikes' => 'nullable|integer|min:0',
            'blocks_volleyball' => 'nullable|integer|min:0',
            'serves' => 'nullable|integer|min:0',
            'digs' => 'nullable|integer|min:0',
            'aces' => 'nullable|integer|min:0',
        ]);

        $statistic->update($validated);

        return redirect()->route('statistics.index')
            ->with('success', 'Statistics updated successfully!');
    }

    public function destroy(Statistic $statistic)
    {
        if ($statistic->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $statistic->delete();

        return redirect()->route('statistics.index')
            ->with('success', 'Statistics deleted successfully!');
    }

    public function summary()
    {
        $userId = auth()->id();

        $summary = [
            'total_games' => Statistic::where('user_id', $userId)->count(),
            'by_sport' => Statistic::where('user_id', $userId)
                ->selectRaw('sport_type, count(*) as count')
                ->groupBy('sport_type')
                ->get(),
            'recent_games' => Statistic::where('user_id', $userId)
                ->orderBy('game_date', 'desc')
                ->take(5)
                ->get(),
        ];

        return response()->json($summary);
    }
}