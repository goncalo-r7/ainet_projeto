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

    public function index(Request $request): View
    {


        $moviesQuery = Movie::query();
        $moviesQuery
            ->select('movies.*')
            ->orderBy('movies.title');
        $filterByTitle = $request->query('title');
        if ($filterByTitle !== null) {
            $moviesQuery
                ->where('movies.title', 'like', "%$filterByTitle%");
        }

        $movies = $moviesQuery->paginate(10)->withQueryString();


        return view('movies.index', compact('movies', 'filterByTitle'));
    }

    public function showCase(Request $request): View
    {

        $genres = Genre::all();
        $moviesQuery = Movie::query();


        $filterByGenre = $request->query('genre');
        $filterByTitle = $request->query('title');
        $filterBySynopsis = $request->query('synopsis');
        $allMoviesBool = $request->has('allMoviesBool') ? '1' : '0';
        //$allMoviesBool = $request->input('allMoviesBool');

        $moviesQuery
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.*')
            ->orderBy('movies.title');
        if ($filterByGenre !== null) {
            $moviesQuery->where('genres.code', $filterByGenre);
        }
        if ($filterByTitle !== null) {
            $moviesQuery
                ->where('movies.title', 'like', "%$filterByTitle%");
        }
        if ($filterBySynopsis !== null) {
            $moviesQuery
                ->where('movies.synopsis', 'like', "%$filterBySynopsis%");
        }
        if ($allMoviesBool !== '1') {

            //FORMA 2 ##### 25ms !!!
            $screenings = Screening::whereBetween('date', [now(), now()->addWeeks(2)])->get();
            $movieIds = $screenings->pluck('movie_id');
            $moviesQuery->whereIn('movies.id', $movieIds);
            $moviesQuery->where('genres.code', 'not like', 'DEFAULT');

        }


        $movies = $moviesQuery->with(['screeningsRef' => function ($query) use ($allMoviesBool) {
            if (!$allMoviesBool) {
                $query->whereBetween('date', [now(), now()->addWeeks(2)]);
            }
        }])->paginate(10)->withQueryString();

        return view('movies.showcase', compact('movies', 'genres', 'filterByGenre', 'filterByTitle', 'filterBySynopsis', 'allMoviesBool'));
    }

    //FORMA 1 ##### 40MS
    // $moviesQuery->whereHas('screeningsRef', function ($query) {
    //     $query->whereBetween('date', [now(), now()->addWeeks(2)]);
    // });
    // $movies = $moviesQuery->with(['screeningsRef' => function ($query) {
    //     $query->whereBetween('date', [now(), now()->addWeeks(2)]);
    // }])->orderBy('title')->paginate(10)->withQueryString();


    //   //FORMA 2 ##### 25ms !!!
    //   $screenings = Screening::whereBetween('date', [now(), now()->addWeeks(2)])->get();
    //   $movieIds = $screenings->pluck('movie_id');
    //   $moviesQuery->whereIn('id', $movieIds);

    public function show(Movie $movie): View
    {
        $genres = Genre::orderBy('name')->pluck('name', 'code')->toArray();
        $screenings = $movie->screeningsRef()->whereBetween('date', [now(), now()->addWeeks(2)])->get();
        // $screenings = $movie->screeningsRef()->get(); luis ver se é admin?


        return view('movies.show')
            ->with('genres', $genres)
            ->with('movie', $movie)
            ->with('screenings', $screenings);
    }


    public function create(): View
    {
        $newMovie = new Movie();
        $genres = Genre::orderBy('name')->pluck('name', 'code')->toArray();
        $firstGenre = reset($genres);
        $newMovie->genre = (object) [
            'name' => $firstGenre
        ];
        return view('movies.create')->with('genres', $genres)->with('movie', $newMovie);
    }

    public function edit(Movie $movie): View
    {
        $genres = Genre::orderBy('name')->pluck('name', 'code')->toArray();
        return view('movies.edit')->with('genres', $genres)->with('movie', $movie);
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $url = route('movies.show', ['movie' => $movie]);

            // Check if there are any active screening sessions for the movie
            $hasActiveScreenings = DB::table('screenings')
            ->where('movie_id', $movie->id)
            ->where('date', '>=', date('Y-m-d'))
            ->exists();

            if (!$hasActiveScreenings) {
                // No active screenings, proceed with deletion
                $movie->delete();
                // // Optionally, delete associated image if it exists
                // if ($movie->imageExists) {
                //     Storage::delete("public/movies/{$movie->fileName}");
                // }

                $alertType = 'success';
                $alertMsg = "Movie {$movie->title} has been deleted successfully!";
            } else {
                // There are active screenings, prevent deletion
                $alertType = 'warning';
                $alertMsg = "Movie <a href='$url'><u>{$movie->title}</u></a> cannot be deleted because there are active screening sessions associated with it.";
            }
            DB::commit();
        } catch (\Exception $error) {
            DB::rollBack();
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the movie <a href='$url'><u>{$movie->title}</u></a> due to an error with the operation!";
        }

        return redirect()->route('movies.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyImage(Movie $movie): RedirectResponse
    {
        if ($movie->imageExists) {
            Storage::delete("public/posters/{$movie->fileName}");
        }
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Image of movie {$movie->name} has been deleted.");
        return redirect()->back();
    }

    public function update(MovieFormRequest $request, Movie $movie): RedirectResponse
    {
        $movie->update($request->validated());

        if ($request->hasFile('image_file')) {
            if ($movie->imageExists) {
                Storage::delete("public/posters/{$movie->fileName}");
            }
            $request->image_file->storeAs('public/posters', $movie->fileName);
        }

        $url = route('movies.show', ['movie' => $movie]);
        $htmlMessage = "Movie <a href='$url'><u>{$movie->name}</u></a> has been updated successfully!";
        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function store(MovieFormRequest $request): RedirectResponse
    {
        $newMovie = Movie::create($request->validated());

        if ($request->hasFile('image_file')) {
            $request->image_file->storeAs('public/posters', $newMovie->fileName);
        }

        $url = route('movies.show', ['movie' => $newMovie]);
        $htmlMessage = "Movie <a href='$url'><u>{$newMovie->name}</u></a> has been created successfully!";
        return redirect()->route('movies.showcase')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }
}
