<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\ConfigurationFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller

{
    public function show(): View
    {
        $configuration = Configuration::first();
        return view('configurations.show', compact('configuration'));
    }

    public function edit(): View
    {
        $configuration = Configuration::first();
        return view('configurations.edit', compact('configuration'));
    }

    public function update(ConfigurationFormRequest $request, Configuration $configuration): RedirectResponse
    {
        $configuration->update($request->validated());


        $url = route('configurations.show');
        $htmlMessage = "Configuration <a href='$url'></a> has been updated successfully!";
        return redirect()->route('configurations.show')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

}
