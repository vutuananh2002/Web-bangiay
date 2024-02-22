<?php

namespace App\Policies\Admin;

use App\Enums\UserPermissionsEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AuthAdminPolicy
{
    use HandlesAuthorization;

    protected $message;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = trans('api.access_denied');
    }

    public function adminRegister(User $user)
    {
        return (
            ($user->isAdministrator() ||
                $user->hasPermission(UserPermissionsEnum::ManageAdmin))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function adminToken(User $user)
    {
        return (
            $user->isAdministrator() && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function adminLogout(User $user)
    {
        return (
            $user->isAdministrator() && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }
}
