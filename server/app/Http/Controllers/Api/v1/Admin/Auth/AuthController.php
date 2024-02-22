<?php

namespace App\Http\Controllers\Api\v1\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthAdminRegisterRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Services\ImageService;
use App\Traits\ApiResponser;
use App\Traits\CookieAuthentication;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser, CookieAuthentication;

    protected $user;
    protected $role;
    protected $admin;
    protected $imageService;

    public function __construct(User $user, Role $role, Admin $admin, ImageService $imageService)
    {
        $this->user = $user;
        $this->role = $role;
        $this->admin = $admin;
        $this->imageService = $imageService;
    }

    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->badRequestResponse(trans('auth.failed'));
        }

        $user = Auth::user();

        if (!$user->isAdministrator()) {
            return $this->accessDeniedResponse(trans('api.access_denied'));
        }

        $accessToken = $user->createToken('accessToken')->plainTextToken;

        if (!$user->isActive()) {
            return $this->notVerifiedResponse($accessToken, trans('auth.not_active'));
        }

        // Send response with cookie.
        $cookie = $this->createAdminCookie($accessToken);
        return $this->responseUserWithAccessTokenAndCookie(new UserResource($user), $accessToken, $cookie, trans('auth.admin_login_success'));
    }

    public function register(AuthAdminRegisterRequest $request)
    {
        Gate::authorize('admin_register');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $validated = $request->validated();
        $credentials = $request->safe()->except(['avatar', 'name', 'phone_number']);
        $credentials['password'] = Hash::make($credentials['password']);
        $adminData = [
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
        ];

        $role = $this->role->administrator();
        $user = $this->user->create($credentials);
        $user->roles()->attach($role->id);
        $user->admin()->create($adminData);

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

        $accessToken = $user->createToken('accessToken')->plainTextToken;
        event(new Registered($user));

        return $this->accessTokenResponse($accessToken, trans('passwords.sent'), 201);
    }

    public function logout(Request $request)
    {
        $this->authorize('admin_logout');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $user = Auth::user();
        $user->tokens()->delete();
        $this->forgetAdminCookie();

        return $this->successReponse(null, trans('auth.logout'));
    }

    public function refreshToken(Request $request)
    {
        $this->authorize('admin_refresh_token');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.access_denied'));

        $user = Auth::user();
        $user->tokens()->delete();
        $this->forgetAdminCookie();

        $accessToken = $user->createToken('accessToken')->plainTextToken;
        $cookie = $this->createAdminCookie($accessToken);

        return $this->responseWithAccessTokenAndCookie($accessToken, $cookie, trans('auth.refresh_token_success'));
    }
}
