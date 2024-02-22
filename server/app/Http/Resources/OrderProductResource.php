<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Color;
use App\Models\Size;
use App\Models\Type;
use App\Enums\ProductTypeEnum;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $created_at = $this->pivot->created_at->format('d/m/Y');
        $updated_at = $this->pivot->updated_at->format('d/m/Y');
        $color = Color::findOrFail($this->pivot->color_id);
        $size = Size::findOrFail($this->pivot->size_id);
        $type = Type::findOrFail($this->pivot->type_id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => number_format($this->price, 0, '', ','),
            'quantity' => $this->pivot->quantity,
            'color' => [
                'id' => $this->pivot->color_id,
                'color_code' => $color->color_code,
            ],
            'size' => [
                'id' => $this->pivot->size_id,
                'name' => $size->name,
            ],
            'type' => [
                'id' => $this->pivot->type_id,
                'type' => ProductTypeEnum::getDescription($type->type_code),
            ],
            'image' => $this->images[0]->url,
            'slug' => $this->slug,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
