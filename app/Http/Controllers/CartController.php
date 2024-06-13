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
        // screening tem 
        // For debug:
        // dump($request->all());
        // dd($screening);
        
        $cart = session('cart', null);
        
        if (!$cart) {
            $cart = collect([]);
            $request->session()->put('cart', $cart);
        } 
        # $screenings to receive in parameter
        // $aa = $screening->where('start_time', '>', now()->subMinutes(5))->get();
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
        $movieStartTime = new DateTime($screening->start_time);
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
            $userId = Auth::id();
            if ($cart->isEmpty()){ // certificar que se se remover todos os elementos do carrinho, a purchase é eliminada
                // criar purchase se n existir
                DB::table('purchases')->insert([
                    'customer_id' => $userId, // TODO alterar para customer_id
                    'date' => now(),
                    'total_price' => 0,
                    'customer_name' => auth()->user()->name, 
                    'customer_email' => auth()->user()->email,
                    'payment_ref' => 123456789, // TODO: alterar para o ref do pagamento
                    //'nif' => auth()->user()->nif ?? null,
                ]);
            } 

            $purchaseId = DB::table('purchases')->where('customer_id', $userId)->orderBy('date', 'desc')->first()->id;
            // one ticket for each seat
            $total=0;
            foreach ($seatsIds as $seatId){
                $ticketId = DB::table('tickets')->insertGetId([
                    'screening_id' => $screening->id,
                    'seat_id' => $seatId,
                    'purchase_id' => $purchaseId,
                    'price' => 5.0, // TODO: alterar para o preço do seat
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $ticketDetails = [
                    'screening_id' => $screening->id,
                    'seat_id' => $seatId,
                    'ticket_id' => $ticketId,
                    'price' => 5.0, // Example price
                ];
                
                // Push the ticket details to the cart
                $cart->push($ticketDetails);

                $total += 5.0; // alterar para preço de cada ticket
            }
            // atualizar purchase
            //$purchase = DB::table('purchases')->where('id', $purchaseId)->first();
            //dd($purchase);
            DB::table('purchases')->where('id', $purchaseId)->increment('total_price', $total);
            session(['cart' => $cart]);
        }
        $alertType = 'success';
        $htmlMessage = "Seats for the movie <strong>\"{$screening->movie->title}\"</strong> were successfully added to the cart.";
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

    public function removeFromCart(Request $request, Ticket $ticket): RedirectResponse
    {
        // this function is not checked !!
        $screeningId = $ticket->screening_id;
        $movieTitle = $ticket->screening->movie->title;
        $url = route('seats.index', ['screening' => $screeningId]);
        $cart = session('cart', null); 
        if (!$cart) {
            $alertType = 'warning';
            $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> for
                <strong>\"{$movieTitle}\"</strong> was not removed from the cart because cart is empty!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $element = $cart->firstWhere('ticket_id', $ticket->id);
            if ($element) {
                $cart->forget($cart->search($element));
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
                }
                DB::table('tickets')->where('id', $ticket->id)->delete();
                $alertType = 'success';
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> for
                <strong>\"{$movieTitle}\"</strong> was removed from the cart.";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> for
                <strong>\"{$movieTitle}\"</strong> was not removed from the cart because cart does not include it!";
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
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }

    // TODO
    public function confirm(CartConfirmationFormRequest $request): RedirectResponse
    {
        $cart = session('cart', null);
        if (!$cart || ($cart->count() == 0)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed, because cart is empty!");
        } else {
            $student = Student::where('number', $request->validated()['student_number'])->first();
            if (!$student) {
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "Student number does not exist on the database!");
            }
            $insertDisciplines = [];
            $disciplinesOfStudent = $student->disciplines;
            $ignored = 0;
            foreach ($cart as $discipline) {
                $exist = $disciplinesOfStudent->where('id', $discipline->id)->count();
                if ($exist) {
                    $ignored++;
                } else {
                    $insertDisciplines[$discipline->id] = [
                        "discipline_id" => $discipline->id,
                        "repeating" => 0,
                        "grade" => null,
                    ];
                }
            }
            $ignoredStr = match($ignored) {
                0 => "",
                1 => "<br>(1 discipline was ignored because student was already enrolled in it)",
                default => "<br>($ignored disciplines were ignored because student was already enrolled on them)"
            };
            $totalInserted = count($insertDisciplines);
            $totalInsertedStr = match($totalInserted) {
                0 => "",
                1 => "1 discipline registration was added to the student",
                default => "$totalInserted disciplines registrations were added to the student",

            };
            if ($totalInserted == 0) {
                $request->session()->forget('cart');
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "No registration was added to the student!$ignoredStr");
            } else {
                DB::transaction(function () use ($student, $insertDisciplines) {
                    $student->disciplines()->attach($insertDisciplines);
                });
                $request->session()->forget('cart');
                if ($ignored == 0) {
                    return redirect()->route('students.show', ['student' => $student])
                        ->with('alert-type', 'success')
                        ->with('alert-msg', "$totalInsertedStr.");
                } else {
                    return redirect()->route('students.show', ['student' => $student])
                        ->with('alert-type', 'warning')
                        ->with('alert-msg', "$totalInsertedStr. $ignoredStr");
                }
            }
        }
    }
}
