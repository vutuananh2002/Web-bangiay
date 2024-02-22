<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeUsernameRequest;
use App\Http\Requests\UpdateUserAvatarRequest;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Image\ImageResource;
use App\Models\User;
use App\Services\ImageService;
use App\Traits\ApiResponser;
use App\Traits\CookieAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponser, CookieAuthentication;

    protected $user;
    protected $imageService;

    public function __construct(User $user, ImageService $imageService)
    {
        $this->user = $user;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $data = [
            'success' => true,
            'message' => 'Lấy danh sách người dùng thành công.',
        ];

        $user = $this->user->paginate(10);

        return (new UserCollection($user))->additional($data)->response();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user = $this->user->findOrFail($user->id);
        $message = 'Lấy thông tin tài khoản thành công.';

        return $this->successReponse(new UserResource($user), $message);
    }

    public function update(ChangeUsernameRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user = $this->user->findOrFail($user->id);
        $user->update($request->validated());

        return $this->successReponse(new UserResource($user), trans('auth.change_username_successfully'));
    }

    /**
     * Delete user account.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user = $this->user->findOrFail($user->id)->deleteOrFail();

        $responseData = null;
        $message = 'Xóa tài khoản thành công.';

        return $this->successReponse($responseData, $message);
    }

    /**
     * Get current Logged User.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function currentUser(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdministrator() && !$this->hasValidBaseToken($request)) {
            return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));
        }

        if ($user->isAdministrator() && !$this->hasValidAdminToken($request)) {
            return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));
        }

        $message = 'Lấy thành công thông tin người dùng.';

        return $this->successReponse(new UserResource($user), $message);
    }

    public function updateAvatar(UpdateUserAvatarRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();
        $user = $this->user->findOrFail($user->id);
        $avatar = $this->imageService->handleUploadedImage($validated['avatar'], 'user', $user->username);
        $user->avatar()->delete();
        $user->avatar()->create($avatar);

        $message = 'Cập nhật avatar thành công.';
        return $this->successCreatedResponse(new ImageResource($user->avatar), $message);
    }
}
