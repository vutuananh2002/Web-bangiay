<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'customer_id',
        'receiver_name',
        'receiver_phone_number',
        'receiver_address',
        'total_price',
        'status',        
        'transaction_id',
    ];
    protected $with = ['customer'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')->withPivot(['quantity', 'color_id', 'size_id', 'type_id'])->withTimestamps();
    }

    public function scopeSalesByMonth(Builder $query, $month = 1)
    {
        return $query->where('status', '=', OrderStatusEnum::Received)
            ->whereRaw("DATE_FORMAT(updated_at, '%Y') = ?", now()->year)
            ->whereRaw("DATE_FORMAT(updated_at, '%m') = ?", $month)
            ->count();
    }

    public function scopeRevenueByMonth(Builder $query, $month = 1)
    {
        return $query->where('status', '=', OrderStatusEnum::Received)
            ->whereRaw("DATE_FORMAT(updated_at, '%Y') = ?", now()->year)
            ->whereRaw("DATE_FORMAT(updated_at, '%m') = ?", $month)
            ->sum('total_price');
    }
}
