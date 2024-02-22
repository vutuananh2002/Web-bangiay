<?php

namespace App\Http\Resources\Admin;

use App\Enums\CustomerGenderEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CustomerResource extends JsonResource
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
        $gender = $this->gender ?? 0;
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'gender' => CustomerGenderEnum::coerce($gender)->value ? 'Nam' : 'Nữ',
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'created_at' => $this->when($isAuth && $isAdmin, $created_at),
            'updated_at' => $this->when($isAuth && $isAdmin, $updated_at),
        ];
    }
}
