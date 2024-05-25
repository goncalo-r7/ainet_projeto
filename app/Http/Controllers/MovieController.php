<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
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
    return view('movies.showcase')->with('movies', $movies);
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
