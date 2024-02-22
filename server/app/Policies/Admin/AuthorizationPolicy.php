<?php

namespace App\Policies\Admin;

use App\Enums\UserPermissionsEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AuthorizationPolicy
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

    public function viewAuthorization(User $user)
    {
        return (
            ($user->isSuperAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageAuthorize))
            && $user->isActive() 
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function createRole(User $user)
    {
        return (
            ($user->isSuperAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageAuthorize))
            && $user->isActive() 
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function createPermission(User $user) 
    {
        return (
            ($user->isSuperAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageAuthorize))
            && $user->isActive() 
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function manageRole(User $user)
    {
        return (
            ($user->isSuperAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageAuthorize))
            && $user->isActive() 
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function managePermission(User $user) 
    {
        return (
            ($user->isSuperAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageAuthorize))
            && $user->isActive() 
        ) ? Response::allow() : Response::deny($this->message);
    }
}
