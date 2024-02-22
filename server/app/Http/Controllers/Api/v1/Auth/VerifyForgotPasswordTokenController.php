<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyForgotPasswordTokenRequest;
use App\Models\PasswordReset;
use App\Traits\ApiResponser;

class VerifyForgotPasswordTokenController extends Controller
{
    use ApiResponser;

    protected $passwordReset;

    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    public function __invoke(VerifyForgotPasswordTokenRequest $request)
    {
        $validated = $request->validated();

        $passwordReset = $this->passwordReset->where('token', $validated['token'])->first();

        if ($passwordReset->isExpire()) {
            return $this->unprocessableEntityResponse('Token đã hết hạn.');
        }

        return $this->successReponse(null, 'Token hợp lệ.');
    }
}
