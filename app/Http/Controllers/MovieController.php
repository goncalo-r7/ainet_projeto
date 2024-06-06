<?php


namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MovieFormRequest;

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


        //FORMA 1 ##### 40MS
        // $moviesQuery->whereHas('screeningsRef', function ($query) {
        //     $query->whereBetween('date', [now(), now()->addWeeks(2)]);
        // });
        // $movies = $moviesQuery->with(['screeningsRef' => function ($query) {
        //     $query->whereBetween('date', [now(), now()->addWeeks(2)]);
        // }])->orderBy('title')->paginate(10)->withQueryString();



        //FORMA 2 ##### 25ms !!!
        $screenings = Screening::whereBetween('date', [now(), now()->addWeeks(2)])->get(); //Mudar para 2 weeks
        $movieIds = $screenings->pluck('movie_id');
        $moviesQuery->whereIn('id', $movieIds);

        $movies = $moviesQuery->with('screeningsRef')->orderBy('title')->paginate(10)->withQueryString();


        return view('movies.showcase', compact('movies', 'genres', 'filterByGenre', 'filterByTitle', 'filterBySynopsis'));
    }

    public function show(Movie $movie): View
    {
        return view('movies.show')->with('movie', $movie);
    }



    public function create(): View
    {
        $newMovie = new Movie();
        return view('movies.create')->with('movie', $newMovie);
    }

    public function edit(Movie $movie): View
    {
        $genres = Genre::orderBy('name')->pluck('name', 'code')->toArray();
        return view('movies.edit')->with('genres', $genres)->with('movie', $movie);
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        try {
            $url = route('movies.show', ['movie' => $movie]);

        } catch (\Exception $error) {
        }
        return redirect()->route('movies.index');

    }

    public function destroyImage(Movie $movie): RedirectResponse
    {
        if ($movie->imageExists) {
            Storage::delete("public/movies/{$movie->fileName}");
        }
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Image of course {$movie->id} has been deleted.");
        return redirect()->back();
    }

    public function update(MovieFormRequest $request, Movie $movie): RedirectResponse
    {
        $movie->update($request->validated());

        if ($request->hasFile('image_file')) {
            if ($movie->imageExists) {
                Storage::delete("public/movies/{$movie->fileName}");
            }
            $request->image_file->storeAs('public/movies', $movie->fileName);
        }

        $url = route('courses.show', ['course' => $course]);
        $htmlMessage = "Course <a href='$url'><u>{$course->name}</u></a> ({$course->abbreviation}) has been updated successfully!";
        return redirect()->route('courses.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }


}
