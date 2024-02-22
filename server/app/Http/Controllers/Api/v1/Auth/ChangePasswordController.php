<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    use ApiResponser;
    
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke(ChangePasswordRequest $request)
    {
        $authUser = Auth::user();
        $validated = $request->validated();

        // Check the user's current password
        if (!Hash::check($validated['current_password'], $authUser->password)) {
            return $this->badRequestResponse(trans('passwords.invalid'));
        }

        // Check if the current password and new password is the same
        if (strcmp($validated['current_password'], $validated['new_password']) === 0) {
            return $this->badRequestResponse(trans('passwords.password_cannot_be_the_same'));
        }

        $user = $this->user->findOrFail($authUser->id);
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        // Whether user check to "Logout me in all devices"
        if ($request->has('logout')) {
            $authUser->tokens()->delete();
        }

        return $this->successReponse(null, trans('passwords.change_password_successfully'));
    }
}
