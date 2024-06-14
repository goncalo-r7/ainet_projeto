<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    public function show(Screening $screening): View
    {
        return view('screenings.show')
            ->with('screening', $screening);

    }
}
