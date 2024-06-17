<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TheaterFormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Seat;


class TheaterController extends Controller
{
    use SoftDeletes;

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

    public function create(): View
    {
        $theater = new Theater();
        return view('theaters.create')
            ->with('theater', $theater);
    }

    public function  store(TheaterFormRequest $request): RedirectResponse
    {
        $theater = $request->validated();
        $insertTheater = new Theater();
        $insertTheater->name = $theater['name'];
        if($request->file('photo_filename') != null && $request->file('photo_filename')->getClientOriginalName() != 'unknown.png'){
            $path = $request->file('photo_filename')->store('public/theaters');
            $path = explode('/', $path);
            $path = $path[2];
            $insertTheater->photo_filename = $path;
        }
        $insertTheater->save();
        $url = route('theaters.show', ['theater' => $insertTheater]);
        $htmlMessage = "Theater <a href='$url'><u>{$insertTheater->name}</u> </a>has been created successfully!";
        return redirect()->route('theaters.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function show(Theater $theater): View
    {
        return view('theaters.show')
            ->with('theater', $theater);
    }
    public function edit(Theater $theater): View
    {
        return view('theaters.edit')
            ->with('theater', $theater);
    }

    public function insertSeats(Request $request, Theater $theater)
    {
        $rows = $theater->seats->unique('row')->count();
        $columns = $theater->seats->where('row', 'A')->count();
        $rowLetter = $theater->seats->max('row');

        // add a row
        if ($rows == 0) {
            $rowLetter = 'A';
            $newSeats = [];
            $newSeats[] = [
                'theater_id' => $theater->id,
                'row' => $rowLetter,
                'seat_number' => 1,
            ];
            Seat::insert($newSeats);
            $rows++;
        } else{
            if ($request->input('row_insert')) { 
                if ($rowLetter == 'Z') {
                    return redirect()->route('theaters.index', $theater->id)
                        ->with('error', 'Cannot insert more rows!');
                }
                $rowLetter = chr(ord($rowLetter) + 1); // new row letter
                $newSeats = [];

                for ($seatNumber = 1; $seatNumber <= $columns; $seatNumber++) {
                    $newSeats[] = [
                        'theater_id' => $theater->id,
                        'row' => $rowLetter,
                        'seat_number' => $seatNumber,
                    ];
                }

                Seat::insert($newSeats); // Bulk insert the new seats
                $rows++; // Increment row count
            } 

            if ($request->input('col_insert') && $columns < 100) {
                if ($columns >= 100) {
                    return redirect()->route('theaters.index', $theater->id)
                        ->with('error', 'Cannot insert more columns!');
                }

                $newColumnNumber = $columns + 1;
                $newSeats = [];

                foreach (range('A', $rowLetter) as $row) {
                    $newSeats[] = [
                        'theater_id' => $theater->id,
                        'row' => $row,
                        'seat_number' => $newColumnNumber,
                    ];
                }
                
                Seat::insert($newSeats);
                $columns++;
            }
        }

        return redirect()->route('theaters.show', $theater->id)
            ->with('success', 'Seats successfully inserted!');
    }

    public function update(TheaterFormRequest $request, Theater $theater): RedirectResponse
    {
        $validated_data = $request->validated();
        if ($request->input('row_insert') || $request->input('col_insert')) {
            $this->insertSeats($request, $theater);
        }

        $theater->update($request->validated());

        $url = route('theaters.show', ['theater' => $theater]);
        if ($request->hasFile('photo_filename')) {
            if ($theater->photo_filename && Storage::exists('public/theaters/' . $theater->photo_filename)) {
                Storage::delete('public/theaters/' . $theater->photo_filename);
            }
            $path = $request->file('photo_filename')->store('public/theaters');
            $theater->photo_filename = basename($path);
        }
        $theater->name = $validated_data['name'];
        $theater->save();
        $htmlMessage = "Theater <a href='$url'><u>{$theater->name}</u></a> has been updated successfully!";
        return redirect()->route('theaters.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Theater $theater): RedirectResponse
    {
        try {
            $url = route('theaters.show', ['theater' => $theater]);
            $totalScreenings = DB::scalar(
                'select count(*) from screenings where theater_id = ? and date < sysdate()',
                [$theater->id]
            );
            $totalSeats = DB::scalar(
                'select count(*) from seats where theater_id = ?',
                [$theater->id]
            );
            if ($totalScreenings == 0 && $totalSeats == 0) {
                Storage::delete("public/theaters/$theater->photo_filename");
                $theater->delete();
                $alertType = 'success';
                $alertMsg = "Theater {$theater->name} has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $screeningsStr = match (true) {
                    $totalScreenings == 0 => "",
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
                $alertMsg = "Theater <a href='$url'><u>{$theater->name}</u></a> cannot be deleted because $justification.";
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
            Storage::delete("public/theaters/$theater->photo_filename");
            $theater->photo_filename = null;
            $theater->save();
        }
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Image of theater {$theater->name} has been deleted.");
        return redirect()->back();
    }

}
