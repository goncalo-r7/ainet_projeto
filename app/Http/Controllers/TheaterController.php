<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TheaterFormRequest;
//use App\Http\Requests\TheaterFormRequest;
use Illuminate\Support\Facades\DB;


class TheaterController extends Controller
{
        /**
     * Display a listing of the resource.
        */

        public function index(): View
        {
            $theatersQuery = Theater::query();
            $theaters = $theatersQuery
                ->orderBy('name')
                ->paginate(20)
                ->withQueryString();
            return view(
                'theaters.index'
            )->with($theaters);
        }
    
    
        /**
         * Show the form for creating a new resource.
         */
        public function create(): View
        {
            $theater = new Theater();
            // $courses no longer required, because it is available through View::share
            // Check AppServiceProvider
            //$courses = Course::all();
            return view('theaters.create')
                ->with('theater', $theater);
        }
    
        /**
         * Store a newly created resource in storage.
         */
        public function  store(TheaterFormRequest $request): RedirectResponse
        {
             $Newtheater = theater::create($request->validated());
             $url = route('theaters.show', ['theater' => $Newtheater]);
             $htmlMessage = "Theater <a href='$url'><u>{$Newtheater->name}</u></a> ({$Newtheater->abbreviation}) has been created successfully!";
             return redirect()->route('theaters.index')
             ->with('alert-type', 'success')
             ->with('alert-msg', $htmlMessage);
        }
    
    
        /**
         * Display the specified resource.
         */
        public function show(Theater $theaters): View
        {
            return view('theaters.show')
                ->with('theater', $theaters);
        }
    
        /**
         * Show the form for editing the specified resource.
         */
        public function edit(Theater $theaters): View
        {
            return view('theaters.edit')
                ->with('theater', $theaters);
        }
    
        /**
         * Update the specified resource in storage.
         */
        public function update(TheaterFormRequest $request, Theater $theater): RedirectResponse
        {
            $theater->update($request->validated());
            $url = route('theaters.show', ['theater' => $theater]);
            $htmlMessage = "theater <a href='$url'><u>{$theater->name}</u></a> has been updated successfully!";
            return redirect()->route('theaters.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', $htmlMessage);
        }
    
    
        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Theater $theater): RedirectResponse
        {
            try {
                $url = route('theaters.show', ['theater' => $theater]);
                $totalScreenings = DB::scalar(
                    'select count(*) from screenings where theater_id = ?',
                    [$theater->id]
                );
                $totalSeats = DB::scalar(
                    'select count(*) from seats where theater_id = ?',
                    [$theater->id]
                );
                if ($totalScreenings == 0 && $totalSeats == 0) {
                    $theater->delete();
                    $alertType = 'success';
                    $alertMsg = "theater {$theater->name} has been deleted successfully!";
                } else {
                    $alertType = 'warning';
                    $screeningsStr = match (true) {
                        $totalScreenings == 1 => "there is 1 screening in this theater",
                        $totalScreenings > 1 => "there are $totalScreenings screening in this theater",
                    };
                    $seatsStr = match (true) {
                        $totalSeats <= 0 => "",
                        $totalSeats == 1 => "it already has 1 teacher",
                        $totalSeats > 1 => "it already has $totalSeats teachers",
                    };
                    $justification = $screeningsStr && $seatsStr
                        ? "$seatsStr and $screeningsStr"
                        : "$seatsStr$screeningsStr";
                    $alertMsg = "theater <a href='$url'><u>{$theater->name}</u></a> cannot be deleted because $justification.";
                }
            } catch (\Exception $error) {
                $alertType = 'danger';
                $alertMsg = "It was not possible to delete the theater
                                <a href='$url'><u>{$theater->name}</u></a>
                                because there was an error with the operation!";
            }
            return redirect()->route('theaters.index')
                ->with('alert-type', $alertType)
                ->with('alert-msg', $alertMsg);
        }
}
