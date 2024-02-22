<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPassword;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use ApiResponser;

    protected $passwordReset;
    protected $user;

    public function __construct(PasswordReset $passwordReset, User $user)
    {
        $this->passwordReset = $passwordReset;
        $this->user = $user;
    }

    public function __invoke(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();

        $user = $this->user->where('email', $validated['email'])->first();
        $this->passwordReset->where('email', $validated['email'])->delete();
        $validated['token'] = Str::random(70);
        $passwordResetData = $this->passwordReset->create($validated);

        if ($passwordResetData) {
            $user->notify(new ResetPassword($passwordResetData->token));
        }

        $responseData = null;
        $message = 'We have e-mailed your password reset link!.';

        return $this->successReponse($responseData, $message);
    }
}
