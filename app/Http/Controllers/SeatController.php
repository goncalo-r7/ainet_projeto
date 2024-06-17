<?php

namespace App\Http\Controllers;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($screeningSessionId)
    {
        $screeningSession = Screening::with(['theater.seats', 'tickets'])->findOrFail($screeningSessionId);

        $seatsByRow = $screeningSession->theater->seats->groupBy('row');
        $cart = session('cart', null);
        return view('seats.index', compact('screeningSession', 'seatsByRow', 'cart'));
    }

}
