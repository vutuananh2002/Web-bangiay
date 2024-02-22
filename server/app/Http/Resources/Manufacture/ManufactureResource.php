<?php

namespace App\Http\Resources\Manufacture;

use App\Http\Resources\Image\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ManufactureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $logo = $this->whenLoaded('image');
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'logo' => new ImageResource($logo),
            'information' => $this->information,
            'slug' => $this->slug,
            'products_count' => $this->products_count,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
