<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Models\Ticket;
use App\Models\Movie;
use App\Models\Purchase;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\ReceiptController;
use App\Models\Screening;
use Illuminate\Support\Facades\Route;


/* ----- PUBLIC ROUTES ----- */

Route::view('/', 'home')->name('home');
Route::get('movies/showcase', [MovieController::class, 'showCase'])->name('movies.showcase');
Route::get('movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/screenings/{screening}/seats', [SeatController::class, 'index'])->name('seats.index');
Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('cart/screenings/{screening}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('cart/remove/{screeningId}/{seatId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('receipts/{purchase}', [ReceiptController::class, 'show'])->name('receipt.show');
//Route::resource('seats', SeatController::class);




/* ----- AUTHENTICATED USERS  ----- */
// Route::middleware('auth', 'verified')->group(function () {
Route::middleware('auth')->group(function () {
    Route::resource('screenings', ScreeningController::class)->only(['index']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('tickets',[TicketController::class, 'index'])->name('tickets.index')->can('viewAny', Ticket::class);
    Route::get('tickets/download/{ticket}', [TicketController::class, 'download'])->name('tickets.download')->can('download','ticket');
    Route::get('tickets/invalidate/{ticket}', [TicketController::class, 'invalidate'])->name('tickets.invalidate')->can('invalidate',Ticket::class);
    Route::post('tickets/verify/{screening}', [TicketController::class, 'verify'])->name('tickets.verify.submit')->can('invalidate',Ticket::class);
    Route::get('tickets/verify/{screening}', [TicketController::class, 'showVerificationForm'])->name('tickets.verify')->can('invalidate',Ticket::class);
    Route::get('tickets/show/{ticket}', [TicketController::class, 'showTicketInfo'])->name('tickets.showinfo')->can('invalidate',Ticket::class);
    Route::resource('users', UserController::class);

    // --------- ADMIN ONLY ROUTES ------------

    Route::middleware('can:admin')->group(function(){
        Route::post('theaters/{theater}/insert-seats', [TheaterController::class, 'insertSeats'])->name('theaters.insertSeats');
        Route::get('configuration/show', [ConfigurationController::class, 'show'])->name('configurations.show');
        Route::get('configuration/edit', [ConfigurationController::class, 'edit'])->name('configurations.edit');
        Route::put('/configurations/{configuration}', [ConfigurationController::class, 'update'])->name('configurations.update');
        Route::get('statistics', [StatisticsController::class, 'show'])->name('statistics.show');
        Route::delete('theaters/{theater}/photo', [TheaterController::class, 'destroyImage'])->name('theaters.photo.destroy');
        Route::delete('movies/{movie}/image', [MovieController::class, 'destroyImage'])->name('movies.image.destroy');
        Route::resource('movies', MovieController::class)->except('show');
        Route::resource('theaters', TheaterController::class);
        Route::resource('genres', GenreController::class);
        Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])->name('administratives.photo.destroy');
        Route::resource('administratives', AdministrativeController::class);
        Route::resource('screenings', ScreeningController::class)->except(['index']);

        Route::resource('customers', CustomerController::class);

});

});
























require __DIR__ . '/auth.php';
