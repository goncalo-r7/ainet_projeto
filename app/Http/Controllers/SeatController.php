<?php

namespace App\Http\Controllers;
use App\Models\Screening;

use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($screeningSessionId)
    {
        $screeningSession = Screening::with(['theater.seats', 'tickets'])->findOrFail($screeningSessionId);

        return view('seats.index', compact('screeningSession'));
    }

    public function submitSeats(Request $request)
    {
        $selectedSeats = $request->input('selectedSeats', []); // Retrieve selected seats from the request

        foreach ($selectedSeats as $seatId) {

            // add to Cart
        }

        return redirect()->back()->with('success', 'Seats selected successfully!');
    }

}
