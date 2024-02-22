<?php

namespace App\Http\Resources\Admin;

use App\Enums\UserRoleEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoleResource extends JsonResource
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

        return [
            'id' => $this->when($isAuth && $isAdmin, $this->id),
            'role' => $this->role,
            'name' => $this->name,
            'created_at' => $this->when($isAuth && $isAdmin, $this->created_at->format('d.m.Y')),
            'updated_at' => $this->when($isAuth && $isAdmin, $this->updated_at->format('d.m.Y')),
        ];
    }
}
