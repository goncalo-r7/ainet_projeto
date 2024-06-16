<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Screening;
use App\Models\Theater;

use App\Models\Movie;
use App\Models\Ticket;

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

        $filterById = $request->query('id');
        if ($filterById !== null) {
            $screeningsQuery->where('screenings.id', $filterById);
        }

        $filterByMovie = $request->query('movie');
        if ($filterByMovie !== null) {
            $screeningsQuery->whereHas('movie', function ($query) use ($filterByMovie) {
                $query->where('title', 'like', "%$filterByMovie%");
            });
        }

        $filterByTheater = $request->query('theater');
        if ($filterByTheater !== null) {
            $screeningsQuery->whereHas('theater', function ($query) use ($filterByTheater) {
                $query->where('name', 'like', "%$filterByTheater%");
            });
        }

        $screenings = $screeningsQuery->get();
        $today = date('Y-m-d');
        $screeningsQuery->where('screenings.date', '>=', $today);
        $screenings = $screeningsQuery->paginate(10)->withQueryString();
        $screeningSoldOut = [];

        foreach ($screenings as $screening) {
            $totalSeats = $screening->theater->seats->count();
            $ticketsSold = $screening->tickets->count();
            $isSoldOut = $ticketsSold >= $totalSeats;
            $screeningSoldOut[$screening->id] = $isSoldOut;
        }

        return view('screenings.index', compact('screenings', 'screeningSoldOut', 'filterById', 'filterByMovie', 'filterByTheater'));
    }


    public function store(ScreeningFormRequest $request): RedirectResponse
    {
        $startTimes = $request->input('start_time');
        $createdScreeningIds = [];
        foreach ($startTimes as $startTime) {
            $validatedData = $request->validated();
            $validatedData['start_time'] = $startTime;
            $newScreening = Screening::create($validatedData);
            $createdScreeningIds[] = $newScreening->id;
        }

        $url = route('screenings.index');
        $htmlMessage = "Screenings with IDs: " . implode(', ', $createdScreeningIds) . " have been created successfully!";
        return redirect($url)
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }





    public function destroy(Screening $screening): RedirectResponse
    {
        try {
            $url = route('screenings.show', ['screening' => $screening]);

            $totalTicketsSold =Ticket::
                where('screening_id', $screening->id)
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
