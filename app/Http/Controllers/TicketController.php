<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function verify(Request $request, $screeningId)
    {
        $request->validate([
            'qrcode_url' => 'required|string',
        ]);


        $screening = Screening::find($screeningId);

        if (!$screening) {
            return back()->with('alert-type', 'danger')->with('alert-msg', 'Screening not found.');
        }
        $ticket = Ticket::where('qrcode_url', $request->qrcode_url)
            ->where('screening_id', $screeningId)
            ->where('status', 'valid')
            ->first();


        if ($ticket) {
            $htmlMessage = "Ticket Valido";
            return redirect()->route('tickets.showinfo', ['ticket' => $ticket])
                ->with('alert-type', 'success')
                ->with('alert-msg', $htmlMessage);


        } else {
            $htmlMessage = "Ticket Invalido";
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', $htmlMessage);
        }
    }

    public function download(Ticket $ticket){
        PdfController::generatePdfTicket($ticket);
    }

    public function show(Ticket $ticket){
        return view('tickets.show', ['ticket'=>$ticket]);
    }

    public function index(Request $request)
    {
        $ticketsQuery = Ticket::query();
        $filterByName = $request->get('ticket');
        if (!empty($filterByName)) {
            $ticketsQuery->where('tickets.id','=', $filterByName);
        }
        if(Gate::allows('view_my', Ticket::class)){
            $ticketsQuery
            ->join('purchases', 'tickets.purchase_id', '=', 'purchases.id')
            ->join('customers', 'purchases.customer_id', '=', 'customers.id')->where('customers.id','=', $request->user()->customer->id);
        }
        $tickets = $ticketsQuery->orderBy('tickets.created_at')->paginate(20)->withQueryString();
        return view(
            'tickets.index'
        )->with('tickets', $tickets)->with('filter', $filterByName);
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
        $ticket->status = 'invalid';
        $ticket->save();

        return redirect()->route('tickets.verify', ['screening' => $ticket->screening_id])
        ->with('alert-type', 'danger')
        ->with('alert-msg', "Ticket {$ticket->id} was invalidated");

    }
}
