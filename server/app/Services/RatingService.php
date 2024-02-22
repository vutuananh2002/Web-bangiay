<?php

namespace App\Services;

use App\Enums\ProductRatingEnum;
use App\Models\Product;

class RatingService
{
    public function getRates()
    {
        $formattedRates = [];
        $rates = ProductRatingEnum::asArray();
        unset($rates['ZeroStar']);

        foreach ($rates as $rate) {
            $formattedRates[] = [
                'rate' => $rate,
                'products_count' => $this->getProductCount($rate),
            ];
        }

        return $formattedRates;
    }

    private function getProductCount($rate)
    {
        return Product::withFilters()
            ->when($rate, function ($query) use ($rate) {
                $query->whereHas('reviews', function ($query) use ($rate) {
                    $query->select('reviews.product_id')
                        ->selectRaw('ROUND(AVG(reviews.star)) as avg_rating')
                        ->groupBy('reviews.product_id')
                        ->havingRaw('ROUND(AVG(reviews.star)) = ?', [$rate]);
                });
            })
            ->count();
    }
}
