<?php


namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class MovieController extends Controller
{
    public function index(): View
    {
        $allMovies = Movie::orderBy('title')->paginate(20);


        return view('movies.index')->with('allMovies', $allMovies);

    }

    public function showCase(Request $request): View
    {

        $genres = Genre::all();
        $moviesQuery = Movie::query();


        $filterByGenre = $request->query('genre');
        $filterByTitle = $request->query('title');
        $filterBySynopsis = $request->query('synopsis');

        $moviesQuery
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.*')
            ->orderBy('movies.title');
        if ($filterByGenre !== null) {
            $moviesQuery->where('genres.name', $filterByGenre);
        }
        if ($filterByTitle !== null) {
            $moviesQuery
                ->where('movies.title', 'like', "%$filterByTitle%");
        }
        if ($filterBySynopsis !== null) {
            $moviesQuery
                ->where('movies.synopsis', 'like', "%$filterBySynopsis%");
        }
        // // $moviesQuery->has('screeningsRef');
        // $movies = $moviesQuery->with('screeningsRef')->orderBy('title')->paginate(10)->withQueryString();

        // Only get movies that have at least one screening from today to the next 2 weeks
        $moviesQuery->whereHas('screeningsRef', function ($query) {
            $query->whereBetween('date', [now(), now()->addWeeks(2)]);
        });

        // Only load the screenings that are happening from today to the next 2 weeks
        $movies = $moviesQuery->with(['screeningsRef' => function ($query) {
            $query->whereBetween('date', [now(), now()->addWeeks(2)]);
        }])->orderBy('title')->paginate(10)->withQueryString();

        return view('movies.showcase', compact('movies', 'genres', 'filterByGenre','filterByTitle','filterBySynopsis'));
    }



    public function create(): View
    {
        $newMovie = new Movie();
        return view('movies.create')->with('movie', $newMovie);
    }

}
