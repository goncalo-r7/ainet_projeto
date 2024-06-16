<?php

namespace App\Http\Controllers;
use App\Models\Screening;

use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($screeningSessionId)
    {
        $screeningSession = Screening::with(['theater.seats', 'tickets'])->findOrFail($screeningSessionId);

        $seatsByRow = $screeningSession->theater->seats->groupBy('row');
        return view('seats.index', compact('screeningSession', 'seatsByRow'));
    }



}
