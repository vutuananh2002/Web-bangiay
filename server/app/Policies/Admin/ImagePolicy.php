<?php

namespace App\Policies\Admin;

use App\Enums\UserPermissionsEnum;
use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ImagePolicy
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
            ($user->isAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Image $image)
    {
        //
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
            ($user->isAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Image $image)
    {
        return (
            ($user->isAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return (
            ($user->isAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::ManageSystem))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Image $image)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Image $image)
    {
        //
    }
}
