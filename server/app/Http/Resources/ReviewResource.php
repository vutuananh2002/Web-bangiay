<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = $this->whenLoaded('customer', $this->customer);
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'customer' => [
                'name' => "{$customer->first_name} {$customer->last_name}",
                'avatar' => $customer->user->avatar->url,
            ],
            'comment' => $this->comment,
            'star' => $this->star,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
