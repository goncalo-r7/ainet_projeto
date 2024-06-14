<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartConfirmationFormRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Discipline;
use App\Models\Student;
use App\Models\Screening;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function show(): View
    {
        $cart = session('cart', null);

        // return view('cart.show', compact('cart'));
        return view('cart.show', compact('cart'));
    }

    public function index($screeningSessionId)
    {
        $screeningSession = Screening::with(['theater.seats', 'tickets'])->findOrFail($screeningSessionId);


        return view('seats.index', compact('screeningSession'));
    }

    // funcao chamada em seats.index (no form)
    public function addToCart(Request $request, Screening $screening): RedirectResponse
    {
        // request tem vetor com os ids dos lugares selecionados
        $cart = session('cart', null);
        $message = '';

        if (!$cart) {
            $cart = collect([]);
            $request->session()->put('cart', $cart);
        }
        # $screenings to receive in parameter
        $seatsIds = $request->input('selectedSeats');
        if (!$seatsIds) {
            $alertType = 'warning';
            $url = route('seats.index', ['screening' => $screening]); // volta para pagina da sessao
            $htmlMessage = "Movie <a href='$url'>#{$screening->id}</a>
            <strong>\"{$screening->movie->title}\"</strong> was not added to the cart because there were no seats selected!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }
        $dateTimeString = $screening->date . ' ' . $screening->start_time;
        $movieStartTime = new DateTime($dateTimeString);
        $now = new DateTime(); // Current time
        $interval = $now->diff($movieStartTime);
        if ($interval->invert == 1 && $interval->i >= 5) { // Invert indicates the interval is negative, meaning now is after start time
            $alertType = 'warning';
            $url = route('seats.index', ['screening' => $screening->id]);
            $htmlMessage = "Ticket <a href='$url'>#{$screening->id}</a> for
            <strong>\"{$screening->movie->title}\"</strong> was not added to the cart because it has already started!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }
        else{
            
            // one ticket for each seat
            $seatsAlreadyInCart = [];
            foreach ($seatsIds as $seatId){
                // $ticketId = DB::table('tickets')->insertGetId([
                //     'screening_id' => $screening->id,
                //     'seat_id' => $seatId,
                //     'price' => 5.0, // TODO: alterar para o preço do seat
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ]);
                // Check if the seat is already in the cart
                $isSeatInCart = $cart->contains(function ($item) use ($seatId, $screening) {
                    return $item['screening_id'] == $screening->id && $item['seat_id'] == $seatId;
                });

                if ($isSeatInCart) {
                    // If the seat is already in the cart
                    $seatsAlreadyInCart[] = $seatId;
                } else{
                    $ticketDetails = [
                        'screening_id' => $screening->id,
                        'seat_id' => $seatId,
                    ];
                }
                $seatDetails = DB::table('seats')->select('row', 'seat_number')->whereIn('id', $seatsAlreadyInCart)->get();
                $message = "Seat {$seatDetails->row}{$seatDetails->seat_number} were already in the cart.";
                return back()
                    ->with('alert-msg', $message)
                    ->with('alert-type', 'warning');
                $message = "Seat {$seatDetails->row}{$seatDetails->seat_number} were already in the cart.";
                // Push the ticket details to the cart
                $cart->push($ticketDetails);
            }
            // atualizar purchase
            //$purchase = DB::table('purchases')->where('id', $purchaseId)->first();
            //dd($purchase);
            //DB::table('purchases')->where('id', $purchaseId)->increment('total_price', $total);
            session(['cart' => $cart]);
        }
        $seatsNames = DB::table('seats')->select('row', 'seat_number')->whereIn('id', $seatsIds)->get();
        $seatStr = '';
        foreach ($seatsNames as $seat) {
            $seatStr .= $seat->row . $seat->seat_number . ', ';
        }
        $seatStr = rtrim($seatStr, ', '); // remove last comma
        $alertType = 'success';
        $htmlMessage = "Seats $seatStr for the movie <strong>\"{$screening->movie->title}\"</strong> were successfully added to the cart.";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    /*public function addToCart(Request $request, Discipline $discipline): RedirectResponse
    {
        $cart = session('cart', null);
        if (!$cart) {
            $cart = collect([$discipline]);
            $request->session()->put('cart', $cart);
        } else {
            if ($cart->firstWhere('id', $discipline->id)) {
                $alertType = 'warning';
                $url = route('disciplines.show', ['discipline' => $discipline]);
                $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}</a>
                <strong>\"{$discipline->name}\"</strong> was not added to the cart because it is already there!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $cart->push($discipline);
            }
        }
        $alertType = 'success';
        $url = route('disciplines.show', ['discipline' => $discipline]);
        $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}</a>
                <strong>\"{$discipline->name}\"</strong> was added to the cart.";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }*/

    public function removeFromCart(Request $request, $screeningId, $seatId): RedirectResponse
    {
        // this function is not checked !!
        // $cart has screening_id and seat_id
        
        $cart = session('cart', null);
        $movieTitle = Screening::find($screeningId)->movie->title;
        $seat = DB::table('seats')->select('row', 'seat_number')->where('id', $seatId)->first();
        $screeningTime = DB::table('screenings')->select('start_time', 'date')->where('id', $screeningId)->first();
        $screeningT = date('H:i', strtotime($screeningTime->start_time)) . ', ' . date('d-m-Y', strtotime($screeningTime->date));
        
        $url = route('seats.index', ['screening' => $screeningId]);
        if (!$cart) {
            $alertType = 'warning';
            return back()
                ->with('alert-msg', "The cart is empty!")
                ->with('alert-type', 'warning');
        } else {
            $element = $cart->search(function ($item) use ($screeningId, $seatId) {
                return $item['screening_id'] == $screeningId && $item['seat_id'] == $seatId;
            });
            if ($element) {
                $cart->forget($cart->search($element));
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
                } else {
                    $request->session()->put('cart', $cart);
                }
                $alertType = 'success';
                $htmlMessage = "Ticket in seat $seat->row$seat->seat_number for <a href='$url'>
                <strong>\"{$movieTitle}\"</strong></a> at $screeningT was removed from the cart.";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Ticket in seat $seat->row$seat->seat_number for <a href='$url'>
                <strong>\"{$movieTitle}\"</strong></a> at $screeningT was not removed from the cart because cart does not include it!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
        }
    }

    /*
    public function removeFromCart(Request $request, Discipline $discipline): RedirectResponse
    {
        // TODO: Alterar para movies
        $url = route('disciplines.show', ['discipline' => $discipline]);
        $cart = session('cart', null);
        if (!$cart) {
            $alertType = 'warning';
            $htmlMessage = "Movie <a href='$url'>#{$discipline->id}</a>
                <strong>\"{$discipline->name}\"</strong> was not removed from the cart because cart is empty!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $element = $cart->firstWhere('id', $discipline->id);
            if ($element) {
                $cart->forget($cart->search($element));
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
                }
                $alertType = 'success';
                $htmlMessage = "Movie <a href='$url'>#{$discipline->id}</a>
                <strong>\"{$discipline->name}\"</strong> was removed from the cart.";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Movie <a href='$url'>#{$discipline->id}</a>
                <strong>\"{$discipline->name}\"</strong> was not removed from the cart because cart does not include it!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
        }
    }*/

    public function destroy(Request $request): RedirectResponse
    {
        // TODO: Confirmar se o destroy dá delete aos tickets e purchase
        $cart = session('cart', null);
         // Delete all tickets associated with the cart from the database
        $purchase = null;
        foreach ($cart as $cartItem) {
            if (!$purchase){
                $purchase = DB::table('tickets')->select('purchase_id')->where('id', $cartItem['ticket_id']);
            }
            if (isset($cartItem['ticket_id'])) {
                DB::table('tickets')->where('id', $cartItem['ticket_id'])->delete();
            }
        }

        // Delete the associated purchase
        if ($purchase) {
            DB::table('purchases')->where('id', $purchase)->delete();
        }

        $request->session()->forget('cart');
        // confirm: destroy is not clearing the seats
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }

    // TODO GONCALO AGORA ESTAMOS NO NOVO DB LOGO NAO HA ESTUDANTES
    public function confirm(CartConfirmationFormRequest $request): RedirectResponse
    {
        $cart = session('cart', null);
        if (!$cart || ($cart->count() == 0)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed, because cart is empty!");
        } 
        $userId = Auth::id();
        // create a purchase
        $purchaseId = DB::table('purchases')->insertGetId([
            'customer_id' => $userId, // TODO alterar para customer_id
            'date' => now(),
            'total_price' => 0,
            'customer_name' => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'payment_ref' => 123456789, // TODO: alterar para o ref do pagamento
            //'nif' => auth()->user()->nif ?? null,
        ]);
        
        // create tickets





        //     $student = Student::where('number', $request->validated()['student_number'])->first();
        //     if (!$student) {
        //         return back()
        //             ->with('alert-type', 'danger')
        //             ->with('alert-msg', "Student number does not exist on the database!");
        //     }
        //     $insertDisciplines = [];
        //     $disciplinesOfStudent = $student->disciplines;
        //     $ignored = 0;
        //     foreach ($cart as $discipline) {
        //         $exist = $disciplinesOfStudent->where('id', $discipline->id)->count();
        //         if ($exist) {
        //             $ignored++;
        //         } else {
        //             $insertDisciplines[$discipline->id] = [
        //                 "discipline_id" => $discipline->id,
        //                 "repeating" => 0,
        //                 "grade" => null,
        //             ];
        //         }
        //     }
        //     $ignoredStr = match($ignored) {
        //         0 => "",
        //         1 => "<br>(1 discipline was ignored because student was already enrolled in it)",
        //         default => "<br>($ignored disciplines were ignored because student was already enrolled on them)"
        //     };
        //     $totalInserted = count($insertDisciplines);
        //     $totalInsertedStr = match($totalInserted) {
        //         0 => "",
        //         1 => "1 discipline registration was added to the student",
        //         default => "$totalInserted disciplines registrations were added to the student",

        //     };
        //     if ($totalInserted == 0) {
        //         $request->session()->forget('cart');
        //         return back()
        //             ->with('alert-type', 'danger')
        //             ->with('alert-msg', "No registration was added to the student!$ignoredStr");
        //     } else {
        //         DB::transaction(function () use ($student, $insertDisciplines) {
        //             $student->disciplines()->attach($insertDisciplines);
        //         });
        //         $request->session()->forget('cart');
        //         if ($ignored == 0) {
        //             return redirect()->route('students.show', ['student' => $student])
        //                 ->with('alert-type', 'success')
        //                 ->with('alert-msg', "$totalInsertedStr.");
        //         } else {
        //             return redirect()->route('students.show', ['student' => $student])
        //                 ->with('alert-type', 'warning')
        //                 ->with('alert-msg', "$totalInsertedStr. $ignoredStr");
        //         }
        //     }
        
}

}
