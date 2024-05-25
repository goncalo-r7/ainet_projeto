<?php


namespace App\Http\Controllers;

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

    public function showCase(): View
    {
        $movies = Movie::orderBy('title')->paginate(10);
        $screenings = Screening::all();
        return view('movies.showcase')->with('movies', $movies)->with('screenings', $screenings);
    }


    public function showCase2(): View
    {
    return view('movies.showcase');
    }

    public function create(): View
    {
        $newMovie = new Movie();
        return view('movies.create')->with('movie', $newMovie);
    }

}
