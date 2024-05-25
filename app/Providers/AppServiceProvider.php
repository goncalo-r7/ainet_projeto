<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
use App\Models\Movie;

use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function (User $user) {
            // Only "administrator" users can "admin"
            return $user->admin;
        });
        try {
            // View::share adds data (variables) that are shared through all views (like global data)
            View::share('courses', Course::orderBy('type')->orderBy('name')->get());
            //View::share('movies', Movie::orderBy('title')->get()); //provavelmente não é necessario
        } catch (\Exception $e) {
            // If no Database exists, or Course table does not exist yet, an error will occour
            // This will ignore this error to avoid problems before migration is correctly executed
            // (When executing "composer update", when executing "php artisan migrate", etc)
        }
    }
}
