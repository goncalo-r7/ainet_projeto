<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function show(): View
{
    $dailyStats = $this->calculateDailyStatistics();

    return view('statistics.show', compact('dailyStats'));
}

private function calculateDailyStatistics(): array
{
    $dates = [];
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $dayOfWeek = date('l', strtotime($date));
        $dates[$date] = $dayOfWeek;
    }

    $screenings = Screening::whereIn('date', array_keys($dates))
        ->with('theater.seats', 'tickets')
        ->get();

    $dailyStats = [];

    foreach ($dates as $date => $dayOfWeek) {
        $dailyStats[$dayOfWeek] = [
            'ticketsSold' => 0,
            'percentage' => 0,
        ];
    }

    foreach ($dailyStats as $dayOfWeek => &$stats) {
        $totalSeats = 0;
        $ticketsSold = 0;

        foreach ($screenings as $screening) {
            $screeningDayOfWeek = date('l', strtotime($screening->date));
            if ($screeningDayOfWeek === $dayOfWeek) {
                $totalSeats += $screening->theater->seats->count();
                $ticketsSold += $screening->tickets->count();
            }
        }
        if ($totalSeats > 0) {
            $stats['ticketsSold'] = $ticketsSold;
            $stats['percentage'] = number_format(($ticketsSold / $totalSeats) * 100, 2);
        } else {
            $stats['ticketsSold'] = 0;
            $stats['percentage'] = 0;
        }
    }

    $dailyStats = array_reverse($dailyStats, true);
    end($dailyStats);
    $lastKey = key($dailyStats);
    $dailyStats['Today'] = $dailyStats[$lastKey];
    unset($dailyStats[$lastKey]);

    return $dailyStats;
}





}
