<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    use ApiResponser;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke(Request $request)
    {
        $user = $this->user->findOrFail($request->id);

        if (!($user->hasVerifiedEmail() && $user->isActive())) {
            $user->markEmailAsVerified();
            $user->markUserAsActive();
            event(new Verified($user));

            $message = 'Email đã được xác nhận.';

            return $this->noContentResponse($message);
        }

        $message = 'Email đã được xác nhận.';

        return $this->noContentResponse($message);
    }

    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return response([
            'data' => [
                'message' => 'Một yêu cầu xác nhận đã được gửi đến địa chỉ email của bạn. Vui lòng kiểm tra hộp thư.',
            ],
        ], 200);
    }
}
