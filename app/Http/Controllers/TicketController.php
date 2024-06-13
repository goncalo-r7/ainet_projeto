<?php

namespace App\Http\Controllers;
use App\Models\Ticket;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function verify(Request $request)
    {

        // Check if there is a ticket matching the provided QR code URL and screening ID
        $ticket = Ticket::where('qrcode_url', $request->qrcode_url)
                        ->where('screening_id', $request->screening_id)
                        ->first();

        if ($ticket) {
            // Ticket found, return success or do something
            $htmlMessage = "Ticket Valido";
            return view('tickets.showinfo', ['ticket' => $ticket])
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);;
        } else {
            // Ticket not found, return error or redirect
            $htmlMessage = "Ticket Invalido";
            return back()
            ->with('alert-type', 'danger')
            ->with('alert-msg', $htmlMessage);;
        }
    }

    public function showVerificationForm()
    {
        return view('tickets.verify');
    }
}
