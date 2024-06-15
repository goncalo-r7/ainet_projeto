<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\ConfigurationFormRequest;
use Illuminate\Http\RedirectResponse;

class ConfigurationController extends Controller

{
    public function show(Configuration $configuration): View
    {

        return view('configurations.show')->with('configuration', $configuration);
    }

    public function edit(Configuration $configuration): View
    {
        return view('configurations.edit')->with('configuration', $configuration);
    }

    public function update(ConfigurationFormRequest $request, Configuration $screening): RedirectResponse
    {
        $screening->update($request->validated());


        $url = route('screenings.show', ['screening' => $screening]);
        $htmlMessage = "Screening <a href='$url'><u>{$screening->id}</u></a> has been updated successfully!";
        return redirect()->route('screenings.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

}
