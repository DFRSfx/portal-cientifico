<?php

namespace App\Http\Controllers;


use App\Models\Output;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    private $cienciaVitae;

    public function __construct() {
        $this->cienciaVitae = new CienciaVitaeController();
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) 
        {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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

        $userHasAuthorInformation = isset($user->authorInformation);

        Auth::logout();

        $user->delete();

        // checks if the user is one author (in this way we can validate if one user has any important information to be deleted without type validation)
        // the polymorfic information is not removed so we have to remove each polymorfic table
        if($userHasAuthorInformation)
        {
            Output::doesntHave("authors")->delete();
            
            // removes all the polymorfic record that has no parent record in the services or outputs table (needs to be done after the user is removed)
            $this->cienciaVitae->removeAllPolymorphicRelations();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
