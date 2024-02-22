<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Product extends BaseModel
{
    protected $table = 'products';
    protected $fillable = [
        'manufacture_id',
        'category_id',
        'name',
        'description',
        'image',
        'stock',
        'price',
        'slug',
    ];
    protected $with = ['categories', 'colors', 'images', 'manufacture', 'reviews', 'sizes', 'types', 'orders'];

    const PRICES = [
        'Dưới 500,000 VND',
        'Từ 500,000 - 1,000,000 VND',
        'Từ 1,000,000 - 2,500,000',
        'Từ 2,500,000 - 5,000,000 VND',
        'Từ 5,000,000 - 10,000,000 VND',
        'Trên 10,000,000 VND',
    ];

    /**
     * Get the manufacture that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class);
    }

    /**
     * Get the categories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id')->withTimestamps();
    }

    /**
     * Get the types that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types()
    {
        return $this->belongsToMany(Type::class, 'product_type', 'product_id', 'type_id')->withTimestamps();
    }

    /**
     * Get the sizes that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id')->withTimestamps();
    }

    /**
     * Get all of the product's images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Get the colors that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'color_product', 'product_id', 'color_id')->withTimestamps();
    }

    /**
     * Get the reviews that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function scopeSearch(Builder $query)
    {
        return request()->input('search', '') ?
            $query->where('name', 'LIKE', '%' . request()->input('search') . '%') :
            $query;
    }

    public function scopeBestSeller(Builder $query)
    {
        $bestSeller = $query->join('order_product', 'order_product.product_id', '=', 'products.id')
            ->selectRaw('products.*, SUM(order_product.quantity) as quantity_sold')
            ->groupBy(['products.id'])
            ->orderBy('quantity_sold', 'desc')
            ->take(10)
            ->get();

        return $bestSeller;
    }

    public function scopeNewArrival(Builder $query)
    {
        $newArrival = $query->orderBy('created_at', 'desc')->take(10)->get();

        return $newArrival;
    }

    public function scopeTopRating(Builder $query)
    {
        $topRating = $query->join('reviews', 'reviews.product_id', '=', 'products.id')
            ->selectRaw('products.*, (SUM(reviews.star) / COUNT(*)) as rating, COUNT(*) as total_review')
            ->groupBy(['products.id'])
            ->orderBy('rating', 'desc')
            ->orderBy('total_review', 'desc')
            ->take(10)
            ->get();

        return $topRating;
    }


    public function scopeWithFilters(Builder $query)
    {
        $categories = request()->input('categories', []);
        $manufactures = request()->input('manufactures', []);
        $colors = request()->input('colors', []);
        $types = request()->input('types', []);
        $sizes = request()->input('sizes', []);
        $prices = request()->input('prices', []);
        $rating = request()->input('rating', '');

        return $query->when(count($categories), function (Builder $query) use ($categories) {
            $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        })
            ->when(count($manufactures), function (Builder $query) use ($manufactures) {
                $query->whereIn('manufacture_id', $manufactures);
            })
            ->when(count($colors), function (Builder $query) use ($colors) {
                $query->whereHas('colors', function ($query) use ($colors) {
                    $query->whereIn('color_id', $colors);
                });
            })
            ->when(count($types), function (Builder $query) use ($types) {
                $query->whereHas('types', function ($query) use ($types) {
                    $query->whereIn('type_id', $types);
                });
            })
            ->when(count($sizes), function (Builder $query) use ($sizes) {
                $query->whereHas('sizes', function ($query) use ($sizes) {
                    $query->whereIn('size_id', $sizes);
                });
            })
            ->when(count($prices), function (Builder $query) use ($prices) {
                $query->where(function (Builder $query) use ($prices) {
                    $query->when(in_array(0, $prices), function (Builder $query) {
                        $query->orWhere('price', '<', '500000');
                    })
                        ->when(in_array(1, $prices), function (Builder $query) {
                            $query->orWhereBetween('price', ['500000', '1000000']);
                        })
                        ->when(in_array(2, $prices), function (Builder $query) {
                            $query->orWhereBetween('price', ['1000000', '2500000']);
                        })
                        ->when(in_array(3, $prices), function (Builder $query) {
                            $query->orWhereBetween('price', ['2500000', '5000000']);
                        })
                        ->when(in_array(4, $prices), function (Builder $query) {
                            $query->orWhereBetween('price', ['5000000', '10000000']);
                        })
                        ->when(in_array(5, $prices), function (Builder $query) {
                            $query->orWhere('price', '>', '10000000');
                        });
                });
            })
            ->when($rating, function (Builder $query) use ($rating) {
                $query->whereHas('reviews', function (Builder $query) use ($rating) {
                    $query->select('reviews.product_id')
                        ->selectRaw('ROUND(AVG(reviews.star)) as avg_rating')
                        ->groupBy('reviews.product_id')
                        ->havingRaw('ROUND(AVG(reviews.star)) = ?', [$rating]);
                });
            });
    }

    public function scopeOrder(Builder $query)
    {
        $orderBy = request()->input('order');
        return $query->when($orderBy, function (Builder $query) use ($orderBy) {
            $query->when($orderBy == 1, function (Builder $query) {
                $query->orderBy('name', 'asc');
            })
            ->when($orderBy == 2, function (Builder $query) {
                $query->orderBy('name', 'desc');
            })
            ->when($orderBy == 3, function (Builder $query) {
                $query->orderBy('price', 'asc');
            })
            ->when($orderBy == 4, function (Builder $query) {
                $query->orderBy('price', 'desc');
            });
        });
    }

    public function scopeSalesByMonth(Builder $query, $month = 1)
    {
        return $query->join('order_product', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
             ->selectRaw("products.name, SUM(order_product.quantity) as quantity_sold,
                DATE_FORMAT(orders.updated_at, '%Y') as year,
                DATE_FORMAT(orders.updated_at, '%m') as month
            ")
            ->groupBy(['products.id', 'year', 'month'])
            ->orderBy('quantity_sold', 'desc')
            ->where('orders.status', '=', OrderStatusEnum::Delivering)
            ->whereRaw("DATE_FORMAT(orders.updated_at, '%Y') = ?", now()->year)
            ->whereRaw("DATE_FORMAT(orders.updated_at, '%m') = ?", $month)
            ->get();
    }
}
