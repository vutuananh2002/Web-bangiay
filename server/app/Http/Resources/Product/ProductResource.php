<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\Manufacture\ManufactureResource;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
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
        $categories = $this->whenLoaded('categories');
        $colors = $this->whenLoaded('colors');
        $images = $this->whenLoaded('images');
        $manufacture = $this->whenLoaded('manufacture');
        $types = $this->whenLoaded('types');
        $sizes = $this->whenLoaded('sizes');
        $reviews = $this->whenLoaded('reviews');
        $total_review = $this->whenLoaded('reviews', $this->reviews->count());
        $rating = $total_review > 0 ? round(($reviews->sum('star') / $total_review), 2) : 0;
        $sold = $this->whenLoaded('orders', $this->orders()->sum('quantity'));
        $created_at = $this->created_at->format('d/m/Y');
        $updated_at = $this->updated_at->format('d/m/Y');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'manufacture' => new ManufactureResource($manufacture),
            'categories' => CategoryResource::collection($categories),
            'types' => TypeResource::collection($types), 
            'sizes' => SizeResource::collection($sizes),
            'colors' => ColorResource::collection($colors),
            'description' => $this->description,
            'total_review' => $total_review,
            'rating' => $rating > 0 ? $rating : 'Không có đánh giá nào.',
            'review_details' => ReviewResource::collection($reviews),
            'sold' => $sold,
            'images' => ImageResource::collection($images),
            'stock' => $this->stock == 0 ? 'Hết hàng' : $this->stock,
            'price' => number_format($this->price, 0, '', ','),
            'slug' => $this->slug,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
