<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\Student;

/* ----- PUBLIC ROUTES ----- */

Route::view('/', 'home')->name('home');
Route::get('courses/showcase', [CourseController::class, 'showCase'])->name('courses.showcase');
Route::get('courses/{course}/curriculum', [CourseController::class, 'showCurriculum'])->name('courses.curriculum');

//MOVIE
Route::get('movies/showcase', [MovieController::class, 'showCase'])->name('movies.showcase');

Route::get('tickets/verify', [TicketController::class, 'verify'])->name('tickets.verify');
Route::get('/verify-ticket', [TicketController::class, 'showVerificationForm'])->name('verify.form');


// ex: /screenings/1, 1 will be passed as the $screeningSessionId parameter to the index method of the SeatController
Route::get('/screenings/{screening}', [SeatController::class, 'index'])->name('seats.index');

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

    Route::delete('courses/{course}/image', [CourseController::class, 'destroyImage'])
        ->name('courses.image.destroy');

    Route::delete('movies/{movie}/image', [MovieController::class, 'destroyImage'])
        ->name('movies.image.destroy');

    //Course show is public and index for any authenticated user
    Route::resource('courses', CourseController::class)->only(['index']);

    //Route::resource('movies', MovieController::class)->only(['index']);
    Route::resource('movies', MovieController::class); // TIRAR ISTO!!! QUANDO LOGS TIVEREM FEITOS

    Route::resource('theaters', TheaterController::class);

    Route::resource('seats', SeatController::class);


    //Department show and index are accessible to any authenticated user
    Route::resource('departments', DepartmentController::class)->only(['index', 'show']);

    //Disciplines index and show are public
    Route::resource('disciplines', DisciplineController::class)->except(['index', 'show']);

    Route::delete('teachers/{teacher}/photo', [TeacherController::class, 'destroyPhoto'])
        ->name('teachers.photo.destroy');
    Route::resource('teachers', TeacherController::class);


    Route::delete('students/{student}/photo', [StudentController::class, 'destroyPhoto'])
        ->name('students.photo.destroy')
        ->can('update', 'student');
    Route::resource('students', StudentController::class);
    // Route::delete('students/{student}/photo', [StudentController::class, 'destroyPhoto'])
    //     ->name('students.photo.destroy')
    //     ->can('update', 'student');
    // Route::get('students', [StudentController::class, 'index'])->name('students.index')
    //     ->can('viewAny', Student::class);
    // Route::get('students/{student}', [StudentController::class, 'show'])
    //     ->name('students.show')
    //     ->can('view', 'student');
    // Route::get('students/create', [StudentController::class, 'create'])
    //     ->name('students.create')
    //     ->can('create', Student::class);
    // Route::post('students', [StudentController::class, 'store'])
    //     ->name('students.store')
    //     ->can('create', Student::class);
    // Route::get('students/{student}/edit', [StudentController::class, 'edit'])
    //     ->name('students.edit')
    //     ->can('update', 'student');
    // Route::put('students/{student}', [StudentController::class, 'update'])
    //     ->name('students.update')
    //     ->can('update', 'student');
    // Route::delete('students/{student}', [StudentController::class, 'destroy'])
    //     ->name('students.destroy')
    //     ->can('delete', 'student');


    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
        ->name('administratives.photo.destroy');
    Route::resource('administratives', AdministrativeController::class);

    // Add movie to cart
    Route::post('cart/screenings/{screening}', [CartController::class, 'addToCart'])
        ->name('cart.add');
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

    Route::middleware('can:admin')->group(function () {
        //Course insert, update and delete related routes are for admin only
        Route::resource('courses', CourseController::class)->except(['index', 'show']);
        //Department insert, update and delete related routes are for admin only
        Route::resource('departments', DepartmentController::class)->except(['index', 'show']);
    });
});

/* ----- OTHER PUBLIC ROUTES ----- */
//Course show is public.
Route::resource('courses', CourseController::class)->only(['show']);

Route::resource('movies', MovieController::class)->only(['show']);
//Disciplines index and show are public
Route::resource('disciplines', DisciplineController::class)->only(['index', 'show']);

require __DIR__ . '/auth.php';
