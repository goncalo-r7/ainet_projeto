<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Optionally reset email verification if email is changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        $customer = $user->customer;
        if ($user->type === 'C') {
            if ($request->has('nif')) {
                $customer->nif = $request->input('nif');
            }

            if ($request->has('payment_type')) {
                $customer->payment_type = $request->input('payment_type');
            }

            if ($request->has('payment_ref')) {
                $customer->payment_ref = $request->input('payment_ref');
            }

            $customer->save();
        }
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
