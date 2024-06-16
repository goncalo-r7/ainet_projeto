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
        // Get today's date in Y-m-d format
        // $today = date('Y-m-d');

        // // Get the start and end dates for the current week (Monday to Sunday)
        // $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        // $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

        // // Count the number of screenings for today
        // $numSessionsToday = Screening::whereDate('date', $today)->count();

        // // Array to hold statistics for the last 12 months
        // $ticketsStatsForYear = [];

        // // Loop through the last 12 months
        // for ($i = 0; $i < 12; $i++) {
        //     // Calculate the start and end dates for the current month
        //     $startOfMonth = date('Y-m-01', strtotime("-$i months"));
        //     $endOfMonth = date('Y-m-t', strtotime("-$i months"));

        //     // Get the screenings for this month
        //     $screeningsOfMonth = Screening::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        //     // Calculate the tickets sold statistics for this month
        //     $ticketsStatsForMonth = $this->calculateTicketsSoldStatistics($screeningsOfMonth);

        //     // Add the statistics to the array with the month as a key
        //     $monthName = date('F Y', strtotime($startOfMonth));
        //     $ticketsStatsForYear[$monthName] = $ticketsStatsForMonth;
        // }
        $numSessionsToday = 0;
        $ticketsStatsForYear = 1;
        return view('statistics.show', compact('numSessionsToday', 'ticketsStatsForYear'));
    }


    private function calculateTicketsSoldStatistics($screenings) //recebe screenings e devolve a percentagem como as contagens the seats vendidos
    {
        $totalSeats = 0;
        $ticketsSold = 0;

        foreach ($screenings as $screening) {
            $totalSeats += $screening->theater->seats->count();
            $ticketsSold += $screening->tickets->count();
        }

        if ($totalSeats === 0) {
            $percentage = 0;
        } else {
            $percentage = ($ticketsSold / $totalSeats) * 100;
        }

        $percentage = number_format($percentage, 2);

        return [
            'totalSeats' => $totalSeats,
            'ticketsSold' => $ticketsSold,
            'percentage' => $percentage
        ];
    }

}
