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
use DateTime;

class CartController extends Controller
{
    
    public function show(): View
    {
        $cart = session('cart', null);
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
            $cart = collect([$screening]);
            $request->session()->put('cart', $cart);
        } else {
            # $screenings to receive in parameter
            // $aa = $screening->where('start_time', '>', now()->subMinutes(5))->get();
            $seatsIds = $request->input('selectedSeats');
            dd($seatsIds);
            if (!$seatsIds) {
                $alertType = 'warning';
                $url = route('seats.index', ['screening' => $screening]); // volta para pagina da sessao
                $htmlMessage = "Movie <a href='$url'>#{$screening->id}</a>
                <strong>\"{$screening->movie->title}\"</strong> was not added to the cart because there were no seats selected!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } elseif ($seatsIds->count() == 0) {
                $alertType = 'warning';
                $url = route('seats.index', ['screening' => $screening]); // volta para pagina da sessao
                $htmlMessage = "Movie <a href='$url'>#{$screening->id}</a>
                <strong>\"{$screening->movie->title}\"</strong> was not added to the cart because there were no seats selected!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
            //$movieStartTime = new DateTime($screening->start_time);
            //$interval = $now->diff($movieStartTime);
            /*if($interval->i > 5){ // 5 minutes after the movie starts
                $alertType = 'warning';
                $url = route('disciplines.show', ['discipline' => $discipline]);
                $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}</a>
                <strong>\"{$discipline->name}\"</strong> was not added to the cart because it has already started!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType); */
            else{
                if ($cart->isEmpty()){ // certificar que se se remover todos os elementos do carrinho, a purchase é eliminada
                    // criar purchase se n existir
                    DB::table('purchases')->insert([
                        'customer_id' => auth()->user()->id,
                        'date' => now(),
                        'total_price' => 0,
                    ]);
                } else{
                    // atualizar purchase
                    $purchaseId = DB::table('purchases')->where('customer_id', auth()->user()->id)->orderBy('date', 'desc')->first();
                    DB::table('purchases')->where('id', $purchaseId)->update([
                        'customer_id' => auth()->user()->id,
                        'date' => now(),
                        'total_price' => 0,
                        'nif' => null,
                    ]); // arranjar maneira obter null
                }

                $purchaseId = DB::table('purchases')->where('customer_id', auth()->user()->id)->orderBy('date', 'desc')->first();
                // one ticket for each seat
                $total=0;
                foreach ($seatsIds as $seatId){
                    DB::table('tickets')->insert([
                        'screening_id' => $screening->id,
                        'seat_id' => $seatId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $total += DB::table('seats')->where('id', $seatId)->first()->price;
                    $total += $seatId->price; // será que a segunda funciona?
                }
                // atualizar purchase
                $currentTotal = DB::table('purchases')->where('id', $purchaseId)->first()->total_price;
                DB::table('purchases')->where('id', $purchaseId)->update([
                    'customer_id' => auth()->user()->id,
                    'date' => now(),
                    'total_price' => $currentTotal + $total,
                ]);

                $cart->push($screening); // adicionar a sessao ao carrinho
            }
        }
        $alertType = 'success';
        // TODO: Alterar para movies
        $url = route('cart.show');
        $htmlMessage = "Seats for movie
                <strong>\"{$screening->movie->title}\"</strong> was added to the cart.";
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
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }


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
