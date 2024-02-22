<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = $this->whenLoaded('products');
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'cart_key' => $this->key,
            'products' => CartProductResource::collection($products),
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
