<?php

namespace App\Policies;

use App\Enums\UserRoleEnum;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CartPolicy
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
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Cart $cart)
    {
        return (
            $user->hasRole(UserRoleEnum::BaseUser) &&
            $user->customer->id === $cart->customer_id
            && $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
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
            $user->hasRole(UserRoleEnum::BaseUser) &&
            $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Cart $cart)
    {
        return (
            $user->hasRole(UserRoleEnum::BaseUser) &&
            $user->customer->id === $cart->customer_id &&
            $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function addProduct(User $user, Cart $cart)
    {
        return (
            $user->hasRole(UserRoleEnum::BaseUser) &&
            $user->customer->id === $cart->customer_id &&
            $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function removeProduct(User $user, Cart $cart) 
    {
        return (
            $user->hasRole(UserRoleEnum::BaseUser) &&
            $user->customer->id === $cart->customer_id &&
            $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }

    public function checkout(User $user, Cart $cart) 
    {
        return (
            $user->hasRole(UserRoleEnum::BaseUser) &&
            $user->customer->id === $cart->customer_id &&
            $user->isActive()
        ) ? Response::allow() : Response::deny($this->message);
    }
}
