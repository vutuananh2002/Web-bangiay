<?php

namespace App\Policies;

use App\Enums\UserPermissionsEnum;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BannerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return (
            ($user->isAdministrator() || $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny(trans('api.access_denied'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return (
            ($user->isAdministrator() || $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny(trans('api.access_denied'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Banner $banner)
    {
        return (
            ($user->isAdministrator() || $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny(trans('api.access_denied'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Banner $banner)
    {
        return (
            ($user->isAdministrator() || $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny(trans('api.access_denied'));
    }
}
