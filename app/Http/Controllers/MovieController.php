<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CourseFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class MovieController extends Controller
{
    public function index(): View
    {
        $allMovies = Movie::orderBy('title')->orderBy('title')->paginate(20);

        return view('movies.index')->with('allMovies', $allMovies);
    }

}
