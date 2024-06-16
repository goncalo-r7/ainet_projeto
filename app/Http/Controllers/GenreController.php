<?php

namespace App\Http\Controllers;
use App\Models\Genre;
use App\Models\Movie;

use Illuminate\View\View;
use App\Http\Requests\GenreFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class GenreController extends Controller
{
    public function index(): View
    {
        return view('genres.index')
        ->with('genres', Genre::orderBy('name')->paginate(20));
    }


    public function show(Genre $genre): View
    {
        return view('genres.show')->with('genre', $genre);
    }

    public function edit(Genre $genre): View
    {
        return view('genres.edit')
            ->with('genre', $genre);
    }

    public function create(): View
    {
        $newGenre = new Genre();
        return view('genres.create')
            ->with('genre', $newGenre);
    }

    public function store(GenreFormRequest $request): RedirectResponse
    {
        $newGenre = Genre::create($request->validated());
        $url = route('genres.show', ['genre' => $newGenre]);
        $htmlMessage = "Genre <a href='$url'><u>{$newGenre->name}</u></a> ({$newGenre->code}) has been created successfully!";
        return redirect()->route('genres.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }


    public function update(GenreFormRequest $request, Genre $genre): RedirectResponse
    {
        $genre->update($request->validated());
        $url = route('genres.show', ['genre' => $genre]);
        $htmlMessage = "Genre <a href='$url'><u>{$genre->name}</u></a> ({$genre->code}) has been created successfully!";
        return redirect()->route('genres.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        //será mais rapido assim uma vez que é mais "raiz"?
            $activeScreenings = DB::scalar(
                'select count(*)
                 from screenings
                 join movies on screenings.movie_id = movies.id
                 where movies.genre_code = ? and screenings.date >= ?',
                [$genre->code, now()]
            );

        if ($activeScreenings) {
            return redirect()->route('genres.index')
                ->with('alert-type', 'danger')
                ->with('alert-msg', 'Cannot delete genre with active screening sessions.');
        }

        Movie::where('genre_code', $genre->code)
        ->update(['genre_code' => 'DEFAULT']);

        $genre->delete();

        return redirect()->route('genres.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Genre has been deleted successfully.');
    }

}
