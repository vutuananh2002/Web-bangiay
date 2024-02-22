<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Category extends BaseModel
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
    ];

    public function scopeSearch(Builder $query)
    {
        return request()->input('search', '') ? $query->where('name', 'LIKE', '%'.request()->input('search').'%') :
        $query;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
