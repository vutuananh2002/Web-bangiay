<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\BaseCollection;

class CategoryCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static $wrap = 'categories';
}
