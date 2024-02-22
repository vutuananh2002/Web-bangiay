<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends JsonResource
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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'products_count' => $this->products_count,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
