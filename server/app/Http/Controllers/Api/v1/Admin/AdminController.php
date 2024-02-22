<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminCollection;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use App\Traits\ApiResponser;
use App\Traits\CookieAuthentication;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponser, CookieAuthentication;

    protected $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Admin::class);

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $data = [
            'success' => true,
            'message' => 'Lấy thành công danh sách admin.',
        ];

        $admins = $this->admin->paginate(10);
        
        return (new AdminCollection($admins))->additional($data)->response();
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Admin $admin)
    {
        $this->authorize('view', $admin);

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $admin = $this->admin->findOrFail($admin->id);
        $message = 'Lấy thành công thông tin admin.';

        return $this->successReponse(new AdminResource($admin), $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdateAdminRequest  $request
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $this->authorize('update', $admin);

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $admin = $this->admin->findOrFail($admin->id);
        $admin->update($request->validated());

        $message = 'Cập nhật thông tin admin thành công.';

        return $this->successReponse(new AdminResource($admin), $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Admin $admin)
    {
        $this->authorize('delete', $admin);

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $admin = $this->admin->findOrFail($admin->id);
        $admin->user->tokens()->delete();
        $admin->user()->delete();
        $admin->deleteOrFail();

        $responseData = null;
        $message = 'Xóa admin thành công.';
        
        return $this->successReponse($responseData, $message);
    }
}
