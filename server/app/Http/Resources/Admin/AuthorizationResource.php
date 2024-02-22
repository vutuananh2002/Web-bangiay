<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');
        $users = $this->whenLoaded('users');
        $permissions = $this->whenLoaded('permissions');

        return [
            'id' => $this->id,
            'role' => $this->role,
            'name' => $this->name,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'users' => UserResource::collection($users),
            'permissions' => PermissionResource::collection($permissions),
        ];
    }
}
