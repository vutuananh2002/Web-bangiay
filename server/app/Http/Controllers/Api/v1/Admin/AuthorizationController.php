<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManagePermissionRequest;
use App\Http\Requests\Admin\ManageRoleRequest;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Resources\Admin\AuthorizationCollection;
use App\Http\Resources\Admin\AuthorizationResource;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\CookieAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthorizationController extends Controller
{
    use ApiResponser, CookieAuthentication;

    protected $role;
    protected $permission;
    protected $user;

    public function __construct(Role $role, Permission $permission, User $user)
    {
        $this->role = $role->with(['users', 'permissions']);
        $this->permission = $permission;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view_authorization');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $data = [
            'success' => true,
            'message' => 'Lấy thành công danh sách phân quyền.',
        ];

        $authorizes = $this->role->paginate(10);

        return (new AuthorizationCollection($authorizes))->additional($data)->response();
    }

    /**
     * Add a new Role.
     * 
     * @param \App\Http\Requests\Admin\StoreRoleRequest $request
     * @return \Illuminate\Http\Response 
     */
    public function storeRole(StoreRoleRequest $request)
    {
        Gate::authorize('create_role');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $role = $this->role->create($request->validated());
        $message = 'Thêm role thành công.';

        return $this->successCreatedResponse(new RoleResource($role), $message);
    }

    /**
     * Add a new Permission.
     * 
     * @param \App\Http\Requests\Admin\StorePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storePermission(StorePermissionRequest $request)
    {
        Gate::authorize('create_permission');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $permission = $this->permission->create($request->validated());
        $message = 'Thêm permission thành công.';

        return $this->successCreatedResponse(new PermissionResource($permission), $message);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Role $role)
    {
        Gate::authorize('view_authorization');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $role = $this->role->findOrFail($role->id);
        $message = 'Lấy thông tin phân quyền thành công.';

        return $this->successReponse(new AuthorizationResource($role), $message);
    }

    /**
     * Assign roles for user or detach roles from user.
     * 
     * @param \App\Http\Requests\Admin\ManageRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function manageRole(ManageRoleRequest $request)
    {
        Gate::authorize('manage_role');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $validated = $request->validated();
        $user_id = $validated['user_id'];
        $role_id = $validated['role_id'];

        $user = $this->user->findOrFail($user_id);
        
        if (!$request->has('action')) {
            return $this->badRequestResponse('Missing Required Parameter');
        }

        switch ($request->action) {
            case 'assign':
                $user->roles()->syncWithoutDetaching($role_id);
                $message = 'Gán role cho người dùng thành công.';
                break;
            
            case 'detach':
                $user->roles()->detach($role_id);
                $message = 'Gỡ role từ người dùng thành công.';
                break;
            
            default:
                return $this->badRequestResponse('Missing Required Parameter');
                break;
        }

        $responseData = null;

        return $this->successCreatedResponse($responseData, $message);
    }

    /**
     * Assign permissions to role or detach permission from role.
     * 
     * @param \App\Http\Requests\Admin\ManagePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function managePermission(ManagePermissionRequest $request)
    {
        Gate::authorize('manage_permission');

        if (!$this->hasValidAdminToken($request)) return $this->accessDeniedResponse(trans('api.token_expired_or_invalid'));

        $validated = $request->validated();
        $role_id = $validated['role_id'];
        $permission_id = $validated['permission_id'];

        $role = $this->role->findOrFail($role_id);

        if (!$request->has('action')) {
            return $this->badRequestResponse('Missing Required Parameter');
        }

        switch ($request->action) {
            case 'assign':
                $role->permissions()->syncWithoutDetaching($permission_id);
                $message = 'Gán permission cho role thành công.';
                break;
            
            case 'detach':
                $role->permissions()->detach($permission_id);
                $message = 'Gỡ permission từ role thành công.';
                break;
            
            default:
                return $this->badRequestResponse('Mission Required Parameter');
                break;
        }

        $responseData = null;
        
        return $this->successCreatedResponse($responseData, $message);
    }
}
