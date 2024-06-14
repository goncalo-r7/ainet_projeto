<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/* ----- PUBLIC ROUTES ----- */

Route::resource('seats', SeatController::class); //tem que estar em primeiro por causa de um conflito qualquer....

Route::view('/', 'home')->name('home');

//MOVIE
Route::get('movies/showcase', [MovieController::class, 'showCase'])->name('movies.showcase');

// Route::get('tickets/show', [TicketController::class, 'verify'])->name('tickets.showinfo');
Route::post('tickets/verify/{screening}', [TicketController::class, 'verify'])->name('tickets.verify.submit');

Route::get('tickets/verify/{screening}', [TicketController::class, 'showVerificationForm'])->name('tickets.verify');
Route::get('tickets/show/{ticket}', [TicketController::class, 'showTicketInfo'])->name('tickets.showinfo');


// ex: /screenings/1, 1 will be passed as the $screeningSessionId parameter to the index method of the SeatController
Route::get('/screenings/{screening}', [SeatController::class, 'index'])->name('seats.index');
// Route::get('screenings/{screeningSessionId}', [SeatController::class, 'index'])->name('seats.index');

/* ----- Non-Verified users ----- */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ----- Verified users ----- */
Route::middleware('auth', 'verified')->group(function () {

    // Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/', 'home')->name('home');



    Route::delete('movies/{movie}/image', [MovieController::class, 'destroyImage'])
        ->name('movies.image.destroy');


    //Route::resource('movies', MovieController::class)->only(['index']);
    Route::resource('movies', MovieController::class); // TIRAR ISTO!!! QUANDO LOGS TIVEREM FEITOS

    Route::resource('theaters', TheaterController::class);














    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
        ->name('administratives.photo.destroy');
    Route::resource('administratives', AdministrativeController::class);

    // Add movie to cart
    Route::post('cart/screenings/{screening}', [CartController::class, 'addToCart'])
        ->name('cart.add');
    // Remove a ticket from the cart:
    // When the route /cart/remove/{ticket} is hit with a DELETE request, Laravel will automatically find the Ticket model with the provided ID
    Route::delete('cart/remove/{screeningId}/{seatId}', [CartController::class, 'removeFromCart'])
        ->name('cart.remove');

//route movie post to seat
    // Route::post('/screenings/{screeningID}', [Screening::class, 'selectSeats'])
    // ->name('seats.index');

    // Add a discipline to the cart:
    /*Route::post('cart/{discipline}', [CartController::class, 'addToCart'])
        ->name('cart.add');
    // Remove a discipline from the cart:
    Route::delete('cart/{discipline}', [CartController::class, 'removeFromCart'])
        ->name('cart.remove'); */
    // Show the cart:
    Route::get('cart', [CartController::class, 'show'])->name('cart.show');
    // Confirm (store) the cart and save disciplines registration on the database:
    Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');
    // Clear the cart:
    Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');

});

/* ----- OTHER PUBLIC ROUTES ----- */

Route::resource('movies', MovieController::class)->only(['show']);

require __DIR__ . '/auth.php';
