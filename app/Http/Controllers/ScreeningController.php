<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Screening;
use App\Models\Theater;

use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\ScreeningFormRequest;
use Illuminate\Support\Facades\DB;


class ScreeningController extends Controller
{
    public function show(Screening $screening): View
    {


        $movies = Movie::orderBy('title')->pluck('title', 'id')->toArray();
        $theaters = Theater::orderBy('name')->pluck('name', 'id')->toArray();

        return view('screenings.show')->with('screening', $screening)->with('movies', $movies)->with('theaters', $theaters);
    }

    public function edit(Screening $screening): View
    {
        $movies = Movie::orderBy('title')->pluck('title', 'id')->toArray();
        $theaters = Theater::orderBy('name')->pluck('name', 'id')->toArray();

        return view('screenings.edit')->with('screening', $screening)->with('movies', $movies)->with('theaters', $theaters);
    }


    public function create(Screening $screening): View
    {
        $movies = Movie::orderBy('title')->pluck('title', 'id')->toArray();
        $theaters = Theater::orderBy('name')->pluck('name', 'id')->toArray();

        return view('screenings.create')->with('screening', $screening)->with('movies', $movies)->with('theaters', $theaters);
    }

    public function update(ScreeningFormRequest $request, Screening $screening): RedirectResponse
    {
        $screening->update($request->validated());


        $url = route('screenings.show', ['screening' => $screening]);
        $htmlMessage = "Screening <a href='$url'><u>{$screening->id}</u></a> has been updated successfully!";
        return redirect()->route('screenings.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function index(Request $request): View
    {
        $screeningsQuery = Screening::query();
        $screeningsQuery->select('screenings.*');

        // Filter by ID
        $filterById = $request->query('id');
        if ($filterById !== null) {
            $screeningsQuery->where('screenings.id', $filterById);
        }

        // Filter by Movie Title
        $filterByMovie = $request->query('movie');
        if ($filterByMovie !== null) {
            $movie = Movie::where('title', 'like', "%$filterByMovie%")->first();
            if ($movie) {
                $movieId = $movie->id;
                $screeningsQuery->where('screenings.movie_id', $movieId);
            } else {
                $screeningsQuery->where('screenings.movie_id', null);
            }
        }
        // Filter by Theater Name
        $filterByTheater = $request->query('theater');
        if ($filterByTheater !== null) {
            $theater = Theater::where('name', 'like', "%$filterByTheater%")->first();
            if ($theater) {
                $theaterId = $theater->id;
                $screeningsQuery->where('screenings.theater_id', $theaterId);
            } else {
                $screeningsQuery->where('screenings.theater_id', null);
            }
        }
        $today = date('Y-m-d');
        $screeningsQuery->where('screenings.date', '>=', $today);

        // Paginate results
        $screenings = $screeningsQuery->paginate(10)->withQueryString();

        // Pass filters to the view
        return view('screenings.index', compact('screenings', 'filterById', 'filterByMovie', 'filterByTheater'));
    }

    public function store(ScreeningFormRequest $request): RedirectResponse
    {
        $newScreening = Screening::create($request->validated());


        $url = route('screenings.show', ['screening' => $newScreening]);
        $htmlMessage = "Screening <a href='$url'><u>{$newScreening->id}</u></a> has been created successfully!";
        return redirect()->route('screenings.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }



    public function destroy(Screening $screening): RedirectResponse
    {
        try {
            $url = route('screenings.show', ['screening' => $screening]);

            $totalTicketsSold = DB::table('tickets')
                ->where('screening_id', $screening->id)
                ->count();

            if ($totalTicketsSold == 0) {
                $screening->delete();


                $alertType = 'success';
                $alertMsg = "Screening {$screening->id} has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $alertMsg = "Screening <a href='$url'><u>{$screening->id}</u></a> cannot be deleted because tickets have already been sold for it.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the screening
                        <a href='$url'><u>{$screening->id}</u></a>
                        because there was an error with the operation!";
        }

        return redirect()->route('screenings.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
