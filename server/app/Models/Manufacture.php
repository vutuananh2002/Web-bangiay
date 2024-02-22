<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Manufacture extends BaseModel
{
    protected $table = 'manufactures';
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'information',
        'slug',
    ];
    protected $with = ['image'];

    /**
     * Get all of the manufacture's products.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the manufacture's images.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function scopeSearch(Builder $query)
    {
        return request()->input('search', '') ? $query->where('name', 'LIKE', '%'.request()->input('search').'%') :
        $query;
    }
}
