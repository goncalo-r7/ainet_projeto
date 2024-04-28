<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DisciplineController;
use Illuminate\Support\Facades\Route;

// REPLACE THIS
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

//WITH THIS
Route::view('/', 'home')->name('home');

// REPLACE THESE 7 ROUTES:
// Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
// Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
// Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
// Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
// Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
// Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
// Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// WITH A SINGLE LINE OF CODE:
Route::resource('courses', CourseController::class);

Route::resource('disciplines', DisciplineController::class);
