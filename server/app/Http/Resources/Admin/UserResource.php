<?php

namespace App\Http\Resources\Admin;

use App\Enums\AccountStatusEnum;
use App\Enums\AccountVerifyEnum;
use App\Http\Resources\Image\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isAuth = Auth::check();
        $isAdmin = $isAuth ? Auth::user()->isAdministrator() : false;
        $roles = $this->whenLoaded('roles');
        $admin = $this->whenLoaded('admin');
        $customer = $this->whenLoaded('customer');
        $is_verified = $this->is_verified ?? 0;
        $email_verified_at = $is_verified ? $this->email_verified_at->format('d/m/Y') : $this->email_verified_at;
        $status = $this->status ?? 0;
        $avatar = $this->whenLoaded('avatar');
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'is_verified' => AccountVerifyEnum::coerce($is_verified)->key,
            'email_verified_at' => $this->when($isAuth && $isAdmin, $email_verified_at),
            'status' => AccountStatusEnum::coerce($status)->key,
            'avatar' => new ImageResource($avatar),
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'roles' => RoleResource::collection($roles),
            'admin' => $this->when($isAuth && $isAdmin, new AdminResource($admin)),
            'customer' => $this->when($isAuth, new CustomerResource($customer)),
        ];
    }
}
