<?php

namespace App\Http\Controllers\Api\v1\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\ImageService;
use App\Traits\ApiResponser;
use App\Traits\CookieAuthentication;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser, CookieAuthentication;

    protected $user;
    protected $role;
    protected $imageService;

    public function __construct(User $user, Role $role, ImageService $imageService)
    {
        $this->user = $user;
        $this->role = $role;
        $this->imageService = $imageService;
    }

    /**
     * Register a new account.
     * 
     * @param \App\Http\Requests\Auth\AuthRegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(AuthRegisterRequest $request)
    {
        $validated = $request->validated();
        $excepts = ['avatar', 'first_name', 'last_name', 'gender', 'address', 'phone_number'];
        $credentials = $request->safe()->except($excepts);
        $credentials['password'] = Hash::make($credentials['password']);
        $customerData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'phone_number' => $validated['phone_number'],
        ];

        $role = $this->role->baseUser();
        $user = $this->user->create($credentials);
        $user->roles()->attach($role->id);
        $user->customer()->create($customerData);
        $accessToken = $user->createToken('accessToken')->plainTextToken;

        if ($request->has('avatar')) {
            $avatar = $this->imageService->handleUploadedImage($validated['avatar'], 'user', $user->username);
            $user->avatar()->create($avatar);
        } else {
            $anonymousAvatar = [
                'url' => env('ANONYMOUS_AVATAR'),
                'expires_at' => null,
            ];
            $user->avatar()->create($anonymousAvatar);
        }

        event(new Registered($user));

        return $this->accessTokenResponse($accessToken, trans('passwords.sent'), 201);
    }

    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->badRequestResponse(trans('auth.failed'));
        }

        $user = Auth::user();

        $accessToken = $user->createToken('accessToken')->plainTextToken;

        if (!$user->isActive()) {
            return $this->notVerifiedResponse($accessToken, trans('auth.not_active'));
        }

        $cookie = $this->createBaseCookie($accessToken);
        $message = $user->isAdministrator() ? trans('auth.using_admin_account') : trans('auth.login_success');

        return $this->responseUserWithAccessTokenAndCookie(new UserResource($user), $accessToken, $cookie, $message);
    }

    public function logout(Request $request)
    {
        if (!$this->hasValidBaseToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        Auth::user()->tokens()->delete();
        $this->forgetBaseCookie();

        return $this->successReponse(null, trans('auth.logout'));
    }

    public function refreshToken(Request $request)
    {
        if (!$this->hasValidBaseToken($request)) return $this->accessDeniedResponse(trans('api.access_denied'));

        $user = Auth::user();
        $user->tokens()->delete();
        $this->forgetBaseCookie();
        
        $accessToken = $user->createToken('accessToken')->plainTextToken;
        $cookie = $this->createBaseCookie($accessToken);

        return $this->responseWithAccessTokenAndCookie($accessToken, $cookie, trans('auth.refresh_token_success'));
    }
}
