<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $fillable = [
        'product_id',
        'customer_id',
        'comment',
        'star'
    ];  

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function scopeTopReview(Builder $query)
    {
        return $query->where('star', '=', 5)->take(10)->get();
    }
}
