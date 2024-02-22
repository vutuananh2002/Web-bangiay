<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $fillable = [
        'key',
        'customer_id',
    ];
    protected $with = ['products'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id', 'product_id')->withPivot(['quantity', 'color_id', 'size_id', 'type_id'])->withTimestamps();
    }

    public function scopeWhereIDCustomerIDAndCartKey(Builder $query, $id, $customer_id, $cart_key)
    {   
        return $query->where([
            ['id', '=', $id],
            ['customer_id', '=', $customer_id],
            ['key', '=', $cart_key],
        ]);
    }
}
