<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function show(): View
    {
        $dailyStats = $this->calculateDailyStatistics();
        $genresPercentages = $this->getGenrePercentages();



        //tratar para receber nos graphs
        $dataTicketsCount = [];
        foreach ($dailyStats as $day => $stats) {
            $dataTicketsCount[] = $stats['ticketsSold'];
        }
        $dataCombined = [];
        foreach ($dailyStats as $day => $stats) {
            $dataCombined[] = $day . ' (' . $stats['percentage'] . '%)';
        }
        $jsonDataTicketsCount = json_encode($dataTicketsCount);
        $jsonDataCombined = json_encode($dataCombined);


        $dataGenreLabel = [];
        foreach ($genresPercentages as $genre => $stats) {
            $dataGenreLabel[] = $stats['genre'];
        }

        $dataGenrePerc = [];
        foreach ($genresPercentages as $genre => $stats) {
            $dataGenrePerc[] = $stats['percentage'];
        }
        $jsonDataGenreLabel = json_encode($dataGenreLabel);
        $jsonDataGenrePerc = json_encode($dataGenrePerc);

        //

        $topMovies = $this->getTopMovies();

        //

        $monthlyRevenue = $this->calculateStatistics();

        $months = [];
        $revenues = [];
        $ticketsSold = [];
        $i=1;
        foreach ($monthlyRevenue as $stats) {
            while ($i < $stats->month){
                $months[] = $i;
                $revenues[] = 0;
                $ticketsSold[] = 0;
                $i++;
            }
            $months[] = $stats->month;
            $revenues[] = $stats->revenue;
            $ticketsSold[] = $stats->tickets_sold;
            $i++;
        }
        $currentMonth = date('n');
        while ($i <= $currentMonth) {
            $months[] = $i;
            $revenues[] = 0;
            $ticketsSold[] = 0;
            $i++;
        }

        $jsonDataDates = json_encode($months);
        $jsonDataRevenues = json_encode($revenues);
        $jsonDataTicketsSold = json_encode($ticketsSold);


        return view('statistics.show', compact('jsonDataTicketsCount','jsonDataCombined','jsonDataGenreLabel','jsonDataGenrePerc', 'topMovies', 'jsonDataDates', 'jsonDataRevenues', 'jsonDataTicketsSold'));
    }

    function calculateStatistics()
    {
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = Ticket::join('purchases', 'tickets.purchase_id', '=', 'purchases.id')
            ->selectRaw('DATE_FORMAT(purchases.date, "%m") AS month, SUM(tickets.price) AS revenue, COUNT(*) AS tickets_sold')
            ->whereYear('purchases.date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        return $monthlyRevenue;
    }

    function getTopMovies()
    {
        $topMovies = Ticket::join('screenings', 'tickets.screening_id', '=', 'screenings.id')
            ->join('movies', 'screenings.movie_id', '=', 'movies.id')
            ->selectRaw('movies.id AS movie_id, movies.title AS movie_title, COUNT(*) AS tickets_sold')
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('tickets_sold')
            ->limit(3)
            ->get();
        return $topMovies;
    }

    function getGenrePercentages()
    {
        $last30Days = date('Y-m-d H:i:s', strtotime('-30 days'));
        $genreCounts = Screening::join('movies', 'screenings.movie_id', '=', 'movies.id')
            ->where('screenings.date', '>=', $last30Days)
            ->where('movies.genre_code', '!=', 'DEFAULT')
            ->select('movies.genre_code')
            ->selectRaw('count(*) as count')
            ->groupBy('movies.genre_code')
            ->orderByDesc('count')
            ->get();
        //usei genre_code para nao ocupar tanto espaco na pie
        $totalScreenings = $genreCounts->sum('count');

        $result = [];
        $i = 0;
        foreach ($genreCounts as $genreCount) {
            if ($i < 5) {
                $percentage = ($genreCount->count / $totalScreenings) * 100;
                $result[] = [
                    'genre' => $genreCount->genre_code,
                    'percentage' => (float)sprintf('%0.2f', $percentage),
                ];
                $i++;
            }
        }

        $otherGenresCount = 0;
        foreach ($genreCounts->slice(5) as $genreCount) {
            $otherGenresCount += $genreCount->count;
        }
        $percentageO = ($otherGenresCount / $totalScreenings) * 100;
        $result[] = [
            'genre' => 'others',
            'percentage' => (float)sprintf('%0.2f', $percentageO),
        ];

        return $result;
    }
    private function calculateDailyStatistics(): array // devolve um array
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
