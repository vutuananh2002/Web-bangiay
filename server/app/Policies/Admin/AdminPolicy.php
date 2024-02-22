<?php

namespace App\Policies\Admin;

use App\Enums\UserPermissionsEnum;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    use HandlesAuthorization;

    protected $message;

    public function __construct()
    {
        $this->message = trans('api.access_denied');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return (
            ($user->isAdministrator() || $user->hasPermission(UserPermissionsEnum::ManageAdmin)) && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Admin $admin)
    {
        return (
            ($user->isAdministrator() || 
            $user->hasPermission(UserPermissionsEnum::ManageAdmin) || 
            $user->id === $admin->user_id) 
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Admin $admin)
    {
        return (
            ($user->isSuperAdministrator() || 
            $user->hasPermission(UserPermissionsEnum::ManageAdmin) ||
            $user->id === $admin->user_id) 
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Admin $admin)
    {
        return (
            ($user->isSuperAdministrator() || 
            $user->hasPermission(UserPermissionsEnum::ManageAdmin) ||
            $user->id === $admin->user_id) 
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }
}
