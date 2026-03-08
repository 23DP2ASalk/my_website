<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Statistic;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }

        // Basketball statistics
        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Basketball',
            'game_date' => now()->subDays(7),
            'points' => 24,
            'assists' => 8,
            'rebounds' => 12,
            'steals' => 3,
            'blocks' => 2,
            'minutes_played' => 35,
            'notes' => 'Great game! Double-double achieved.'
        ]);

        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Basketball',
            'game_date' => now()->subDays(14),
            'points' => 18,
            'assists' => 6,
            'rebounds' => 9,
            'steals' => 2,
            'blocks' => 1,
            'minutes_played' => 32,
            'notes' => 'Good defensive performance.'
        ]);

        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Basketball',
            'game_date' => now()->subDays(21),
            'points' => 31,
            'assists' => 10,
            'rebounds' => 7,
            'steals' => 4,
            'blocks' => 3,
            'minutes_played' => 38,
            'notes' => 'Career high in points!'
        ]);

        // Football statistics
        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Football',
            'game_date' => now()->subDays(5),
            'goals' => 2,
            'assists_football' => 1,
            'shots' => 7,
            'passes' => 45,
            'tackles' => 3,
            'minutes_played' => 90,
            'notes' => 'Scored two goals in the second half!'
        ]);

        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Football',
            'game_date' => now()->subDays(12),
            'goals' => 1,
            'assists_football' => 2,
            'shots' => 5,
            'passes' => 52,
            'tackles' => 2,
            'minutes_played' => 85,
            'notes' => 'Good playmaking performance.'
        ]);

        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Football',
            'game_date' => now()->subDays(19),
            'goals' => 0,
            'assists_football' => 3,
            'shots' => 3,
            'passes' => 67,
            'tackles' => 4,
            'minutes_played' => 90,
            'notes' => 'Three assists! Excellent vision on the field.'
        ]);

        // Volleyball statistics
        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Volleyball',
            'game_date' => now()->subDays(3),
            'spikes' => 15,
            'blocks_volleyball' => 4,
            'serves' => 12,
            'digs' => 8,
            'aces' => 3,
            'minutes_played' => 75,
            'notes' => 'Strong attacking game with 3 aces!'
        ]);

        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Volleyball',
            'game_date' => now()->subDays(10),
            'spikes' => 12,
            'blocks_volleyball' => 6,
            'serves' => 10,
            'digs' => 10,
            'aces' => 2,
            'minutes_played' => 80,
            'notes' => 'Excellent blocking at the net.'
        ]);

        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Volleyball',
            'game_date' => now()->subDays(17),
            'spikes' => 18,
            'blocks_volleyball' => 3,
            'serves' => 15,
            'digs' => 6,
            'aces' => 4,
            'minutes_played' => 70,
            'notes' => 'Season high in spikes and aces!'
        ]);

        // Recent games
        Statistic::create([
            'user_id' => $user->id,
            'sport_type' => 'Basketball',
            'game_date' => now()->subDays(1),
            'points' => 27,
            'assists' => 9,
            'rebounds' => 11,
            'steals' => 5,
            'blocks' => 2,
            'minutes_played' => 36,
            'notes' => 'Near triple-double! Great all-around game.'
        ]);
    }
}
