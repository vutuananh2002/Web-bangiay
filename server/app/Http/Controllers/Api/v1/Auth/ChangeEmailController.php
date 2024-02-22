<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeEmailRequest;
use App\Models\User;
use App\Notifications\EmailChange;
use App\Notifications\VerifyEmailChange;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ChangeEmailController extends Controller
{
    /*
    |----------------------------------------------------------------------------|
    | Email Change Controller                                                    |
    |----------------------------------------------------------------------------|
    |                                                                            |
    | This controller allows the user to change the email address after the user |
    | verifies it through a message delivered to the new email address.          |
    |                                                                            |
    |----------------------------------------------------------------------------|
    */

    use ApiResponser;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke(ChangeEmailRequest $request)
    {
        $authUser = Auth::user();
        $validated = $request->validated();

        // Send the email to the user's current email.
        $authUser->notify(new EmailChange($validated['email']));

        // Send the email to the new email address.
        Notification::route('mail', $validated['email'])
            ->notify(new VerifyEmailChange($authUser));

        return $this->successReponse(null, trans('auth.sent_email_change_notification'));
    }

    public function verify(ChangeEmailRequest $request)
    {
        $validated = $request->validated();
        $user = $this->user->findOrFail($request->id);
        $user->update($validated);

        return $this->successReponse(null, trans('auth.change_email_successfully'));
    }
}
