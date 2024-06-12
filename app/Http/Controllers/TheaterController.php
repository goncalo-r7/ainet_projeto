<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TheaterFormRequest;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;


class TheaterController extends Controller
{


    public function index(Request $request): View
    {
        $theatersQuery = Theater::query();
        $filterByName = $request->get('theater');
        if (!empty($filterByName)) {
            $theatersQuery->where('name', 'like', '%' . $filterByName . '%');
        }
        $theaters = $theatersQuery->orderBy('name')->paginate(20)->withQueryString();
        return view(
            'theaters.index'
        )->with('theaters', $theaters)->with('filter', $filterByName);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $theater = new Theater();
        // $theaters no longer required, because it is available through View::share
        // Check AppServiceProvider
        //$theaters = theater::all();
        return view('theaters.create')
            ->with('theater', $theater);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function  store(TheaterFormRequest $request): RedirectResponse
    {
        $theater = $request->validated();
        $insertTheater = new Theater();
        $insertTheater->name = $theater['name'];
        if($request->file('photo_filename') != null){
            $path = $request->file('photo_filename')->store('public/photos');
            $insertTheater->photo_filename = $path;
        }
        $insertTheater->save();
        $url = route('theaters.show', ['theater' => $insertTheater]);
        $htmlMessage = "Theater <a href='$url'><u>{$insertTheater->name}</u> </a>has been created successfully!";
        return redirect()->route('theaters.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }


    /**
     * Display the specified resource.
     */
    public function show(Theater $theater): View
    {
        return view('theaters.show')
            ->with('theater', $theater);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theater $theater): View
    {
        return view('theaters.edit')
            ->with('theater', $theater);
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(TheaterFormRequest $request, Theater $theater): RedirectResponse
    {

        $validated_data = $request->validated();
        $theater->update($request->validated());
        $url = route('theaters.show', ['theater' => $theater]);
        if ($request->hasFile('photo_filename')) {
            // Deletar o arquivo anterior (se houver)
            if ($theater->photo_filename && Storage::exists('public/photos/' . $theater->photo_filename)) {
                Storage::delete('public/photos/' . $theater->photo_filename);
            }
            // Armazenar o novo arquivo
            $path = $request->file('photo_filename')->store('public/photos');
            $theater->photo_filename = basename($path);
        }
        $theater->name = $validated_data['name'];
        $theater->save();
        $htmlMessage = "Theater <a href='$url'><u>{$theater->name}</u></a> has been updated successfully!";
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
                    $totalSeats == 1 => "it has a seat associated to this theater",
                    $totalSeats > 1 => "it has $totalSeats seats associated",
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

    public function destroyImage(Theater $theater): RedirectResponse
    {
        if ($theater->photo_filename != null) {
            Storage::delete("public/photos/$theater->photo_filename");
            $theater->photo_filename = null;
            $theater->save();
        }
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Image of theater {$theater->name} has been deleted.");
        return redirect()->back();
    }
}
