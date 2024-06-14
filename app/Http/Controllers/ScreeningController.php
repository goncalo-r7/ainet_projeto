<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\ScreeningFormRequest;

class ScreeningController extends Controller
{
    public function show(Screening $screening): View
    {
        return view('screenings.show')
            ->with('screening', $screening);

    }

    public function edit(Screening $screening): View
    {
        return view('screenings.edit')->with('screening', $screening);
    }

    public function update(ScreeningFormRequest $request, Screening $screening): RedirectResponse
    {
        $screening->update($request->validated());


        $url = route('screenings.show', ['screening' => $screening]);
        $htmlMessage = "Screening <a href='$url'><u>{$screening->id}</u></a> has been updated successfully!";
        return redirect()->route('screenings.show')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }
}
