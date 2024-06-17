<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartConfirmationFormRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Discipline;
use App\Models\Purchase;
use App\Models\Student;
use App\Models\Screening;
use App\Models\Ticket;
use App\Services\Payment;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use PSpell\Config;
use App\Models\Configuration;
use App\Models\Seat;

class CartController extends Controller
{

    public function show(): View
    {
        $cart = session('cart', null);

        $user = Auth::user();
        $isCustomer = false;
        $customerData = [];
        
        if ($user) {
            $customer = Customer::find($user->id);
            if ($customer) {
                $isCustomer = true;
                $customerData = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'nif' => $customer->nif,
                    'payment_type' => $customer->payment_type,
                    'payment_ref' => $customer->payment_ref,
                ];
            }
        }
    
        $conf = Configuration::first();
        $price = 0;
        $discount = 0;
        if ($cart){
            $price = $conf->ticket_price * count($cart);
            $discount = $isCustomer ? $conf->registered_customer_ticket_discount * count($cart) : 0;
        }
        $ticketdata = collect($cart)->map(function($ticket) {
            $screening = Screening::with('movie', 'theater')->find($ticket['screening_id']);
            $seat = Seat::find($ticket['seat_id']);
    
            return [
                'screening' => $screening,
                'movieTitle' => $screening->movie->title,
                'theaterName' => $screening->theater->name,
                'seat' => $seat->row . $seat->seat_number,
                'seat_id' => $seat->id,
                'url' => route('seats.index', ['screening' => $screening->id]),
            ];
        });

        return view('cart.show', compact('cart', 'ticketdata', 'price', 'discount', 'isCustomer', 'customerData'));
    }

    public function index($screeningSessionId)
    {
        $screeningSession = Screening::with(['theater.seats', 'tickets'])->findOrFail($screeningSessionId);


        return view('seats.index', compact('screeningSession'));
    }

    public function addToCart(Request $request, Screening $screening): RedirectResponse
    {
        $cart = session('cart', null);
        $message = '';

        if (!$cart) {
            $cart = collect([]);
            $request->session()->put('cart', $cart);
        }
        $seatsIds = $request->input('selectedSeats');
        if (!$seatsIds) {
            $alertType = 'warning';
            $url = route('seats.index', ['screening' => $screening]);
            $htmlMessage = "Tickets <a href='$url'> for
            <strong>\"{$screening->movie->title}\"</strong></a> were not added to the cart because there were no seats selected!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }
        $dateTimeString = $screening->date . ' ' . $screening->start_time;
        $movieStartTime = new DateTime($dateTimeString);
        $now = new DateTime();
        $interval = $now->diff($movieStartTime);
        if ($interval->invert == 1 && $interval->i >= 5) { 
            $alertType = 'warning';
            $url = route('seats.index', ['screening' => $screening->id]);
            $htmlMessage = "Tickets for <a href='$url'>#{$screening->id}</a> 
            <strong>\"{$screening->movie->title}\"</strong> were not added to the cart because the screening has already started!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }
        else{
            
            $seatsAlreadyInCart = [];
            $isRepeated = false;
            foreach ($seatsIds as $seatId){
                $isSeatInCart = $cart->contains(function ($item) use ($seatId, $screening) {
                    return $item['screening_id'] == $screening->id && $item['seat_id'] == $seatId;
                });

                if ($isSeatInCart) {
                    // If the seat is already in the cart
                    $seatsAlreadyInCart[] = $seatId;
                    $isRepeated = true;
                } else{
                    $ticketDetails = [
                        'screening_id' => $screening->id,
                        'seat_id' => $seatId,
                    ];
                    $cart->push($ticketDetails);
                }
                
            }
            if ($isRepeated) {
                $seatsCart = Seat::select('row', 'seat_number')->whereIn('id', $seatsAlreadyInCart)->get();
                $strAux = '';
                foreach ($seatsCart as $seat) {
                    $strAux .= $seat->row . $seat->seat_number . ', ';
                }
                $strAux = rtrim($strAux, ', '); // remove last comma
                $message = "Seats {$strAux} were already in the cart.<br>";
            }
            session(['cart' => $cart]);
        }
        $newSeats = Seat::select('row', 'seat_number')
                ->whereIn('id', $seatsIds)
                ->whereNotIn('id', $seatsAlreadyInCart)
                ->get();
        $seatStr = '';
        foreach ($newSeats as $seat) {
            $seatStr .= $seat->row . $seat->seat_number . ', ';
        }
        $seatStr = rtrim($seatStr, ', '); // remove last comma
        $alertType = 'success';
        $htmlMessage = $newSeats->isEmpty() 
                            ? $message
                            : $message . "Seats $seatStr for the movie <strong>\"{$screening->movie->title}\"</strong> were successfully added to the cart." ;
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }
    public function removeFromCart(Request $request, $screeningId, $seatId): RedirectResponse
    {
        $cart = session('cart', null);
        $movieTitle = Screening::find($screeningId)->movie->title;
        $seat = Seat::find($seatId);
        $screeningTime = Screening::select('start_time', 'date')->where('id', $screeningId)->first();
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
            if ($element !== false) {
                $cart->forget($element);
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
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

    public function destroy(Request $request): RedirectResponse
    {
        $cart = session('cart', null);

        $request->session()->forget('cart');
        // confirm: destroy is not clearing the seats
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
        }

        // check if any of the screenings has already started
        $screeningIds = [];
        foreach ($cart as $ticket) {
            // Check if the screening ID is already in the array
            if (!in_array($ticket['screening_id'], $screeningIds)) {
                $screeningIds[] = $ticket['screening_id'];
            }
        }
        foreach ($screeningIds as $sId){
            $screening = Screening::find($sId);
            $dateTimeString = $screening->date . ' ' . $screening->start_time;
            $movieStartTime = new DateTime($dateTimeString);
            $now = new DateTime(); // Current time
            $interval = $now->diff($movieStartTime);
            if ($interval->invert == 1 && $interval->i >= 5) { // Invert indicates the interval is negative, meaning now is after start time
                $alertType = 'warning';
                $url = route('cart.show', ['screening' => $screening->id]);
                $htmlMessage = "Purchase was not confirmed because screening <a href='$url'>#{$screening->id}</a> 
                <strong>\"{$screening->movie->title}\"</strong> has already started!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
        }
        $user = Auth::user();
        $customer = null;
        if ($user){
            $customer = Customer::find($user->id);
        }
        $conf = Configuration::first(); // to get the price
        $pricePerTicket = $conf->ticket_price;
        $price = $pricePerTicket * count($cart);
        $purchaseId = null;
        $validatedData = $request->validated();

        // Validate payment details
        $paymentType = $validatedData['payment_type'];
        $paymentRef = $validatedData['payment_ref'];
        // validate payment
        if ($paymentType == 'VISA'){
            $card_number = substr($paymentRef, 0, 16);
            $cvc_code = substr($paymentRef, 16, 3);
            if (!Payment::payWithVisa($card_number, $cvc_code)){ // invalid payment
                $validator = Validator::make([], []);
                $validator->errors()->add('payment_ref', 'Payment reference should consist in 16 digits + 3-digit CVC code.');
                throw new ValidationException($validator);
            }
        } elseif ($paymentType == 'PAYPAL'){
            if (!Payment::payWithPaypal($paymentRef)){ // invalid payment
                $validator = Validator::make([], []);
                $validator->errors()->add('payment_ref', 'Invalid payment reference. Please provide a valid email address.');
                throw new ValidationException($validator);
            }
        } else{
            if (!Payment::payWithMBway($paymentRef)){ // invalid payment
                $validator = Validator::make([], []);
                $validator->errors()->add('payment_ref', 'Invalid payment reference. Please provide a valid phone number.');
                throw new ValidationException($validator);
            }
        }
        $strAux = '';
        DB::beginTransaction();
        try{
            if ($customer){ // is a registered customer
                // in this case $request will only receive payment_type, payment_ref and pdf_filename
                $price = $price - ($conf->registered_customer_ticket_discount * count($cart));
                $pricePerTicket = $pricePerTicket - $conf->registered_customer_ticket_discount;
                $purchaseId = DB::table('purchases')->insertGetId([
                    'customer_id' => $customer->id,
                    'date' => now(),
                    'total_price' => $price,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'nif' => $customer->nif ?? null,
                    'payment_type' => $request['payment_type'],
                    'payment_ref' => $request['payment_ref'], 
                    'receipt_pdf_filename' => null
                ]);
            } else{ 
                $purchaseId = DB::table('purchases')->insertGetId([
                    'customer_id' => null,
                    'date' => now(),
                    'total_price' => $price,
                    'customer_name' => $request['customer_name'],
                    'customer_email' => $request['customer_email'],
                    'nif' => $request['customer_nif'] ?? null,
                    'payment_type' => $request['payment_type'],
                    'payment_ref' => $request['payment_ref'], 
                    'receipt_pdf_filename' => null
                ]);
            }

            foreach ($cart as $ticket) {
                DB::table('tickets')->insert([
                    'screening_id' => $ticket['screening_id'],
                    'seat_id' => $ticket['seat_id'],
                    'purchase_id' => $purchaseId,
                    'price' => $pricePerTicket,
                    'qrcode_url' => 'http://ainet_projeto.test/' . md5($ticket['screening_id'] . $ticket['seat_id'] . $purchaseId),
                    'status' => 'valid',
                ]);
            }
            // generate pdf and qr codes
            $purchase = Purchase::find($purchaseId);
            PdfController::generatePdfReceipt($purchase);
            $urlPdf = route('receipt.show', ['purchase' => $purchaseId]);
            $purchase->receipt_pdf_filename = $urlPdf;

            foreach ($screeningIds as $screen) {
                $url = route('seats.index', ['screening' => $screen]);
                $strAux .= '<a href="' . $url . '">#' . $screen . '</a>, ';
            }
            $strAux = rtrim($strAux, ', '); // remove last comma
            $request->session()->forget('cart');
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', 'There was an error processing your purchase. Please try again.');
        }
        
        return redirect()->route('home')
            ->with('alert-type', 'success')
            ->with('alert-msg', "Purchase for screening {$strAux} was successfully completed!");

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
