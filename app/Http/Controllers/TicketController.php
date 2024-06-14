<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\Ticket;

use Illuminate\Http\Request;

class TicketController extends Controller
{


    // public function show(Ticket $ticket): View
    // {
    //     // $user = Genre::orderBy('name')->pluck('name', 'code')->toArray();
    //     return view('tickets.show')->with('ticket', $ticket);
    // }

    public function verify(Request $request, $screeningId)
    {
        // Validate the request
        $request->validate([
            'qrcode_url' => 'required|string',
        ]);

        // Find the screening by $screeningId
        $screening = Screening::find($screeningId);

        if (!$screening) {
            return back()->with('alert-type', 'danger')->with('alert-msg', 'Screening not found.');
        }

        // Check if there is a ticket matching the provided QR code URL and screening ID
        $ticket = Ticket::where('qrcode_url', $request->qrcode_url)
            ->where('screening_id', $screeningId)
            ->where('status', 'valid')
            ->first();


        if ($ticket) {
            // Ticket found, return success
            $htmlMessage = "Ticket Valido";
            return redirect()->route('tickets.showinfo', ['ticket' => $ticket])
                ->with('alert-type', 'success')
                ->with('alert-msg', $htmlMessage);
        } else {
            // Ticket not found, return error
            $htmlMessage = "Ticket Invalido";
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', $htmlMessage);
        }
    }


    public function showVerificationForm($screeningId)
    {
        $screening = Screening::find($screeningId);
        return view('tickets.verify', compact('screening'));
    }

    public function showTicketInfo(Ticket $ticket)
    {
        return view('tickets.showinfo', compact('ticket'));
    }

    public function invalidate(Ticket $ticket)
    {
        // // Perform ticket invalidation logic here (update status, etc.)
        $ticket->status = 'invalid'; // Example logic, adjust as per your application's needs
         $ticket->save();

        // Redirect back to the verify page with a message
        $screening = Screening::find($ticket->screening_id);
        return redirect()->route('tickets.verify', ['screening' => $ticket->screening_id])
        ->with('alert-type', 'danger')
        ->with('alert-msg', 'Ticket was invalidated');
        // $htmlMessage = "Ticket was invalidated ";
        // return view('tickets.verify', compact('screening'))->with('alert-type', 'danger')->with('alert-msg', $htmlMessage);
    }
}
