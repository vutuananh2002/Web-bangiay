<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ApiResponser;

    protected $passwordReset;
    protected $user;

    public function __construct(PasswordReset $passwordReset, User $user)
    {
        $this->passwordReset = $passwordReset;
        $this->user = $user;
    }

    public function __invoke(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $passwordReset = $this->passwordReset->where('token', $validated['token'])->first();

        if ($passwordReset->isExpire()) {
            return $this->unprocessableEntityResponse('Token đã hết hạn.');
        }

        $user = $this->user->where('email', $passwordReset->email);

        $validated['password'] = Hash::make($validated['password']);

        $user->update([
            'password' => $validated['password'],
        ]);

        $passwordReset->delete();

        return $this->successReponse(null, 'Reset mật khẩu thành công.');
    }
}
