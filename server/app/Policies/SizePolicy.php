<?php

namespace App\Policies;

use App\Models\Size;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserPermissionsEnum;
use Illuminate\Auth\Access\Response;

class SizePolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Size $size)
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
            $user->hasPermission(UserPermissionsEnum::CreateProductSizes))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Size $size)
    {
        return (
            ($user->isAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::EditProductSizes))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Size $size)
    {
        return (
            ($user->isAdministrator() ||
            $user->hasPermission(UserPermissionsEnum::DeleteProductSizes))
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Size $size)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Size $size)
    {
        //
    }
}
