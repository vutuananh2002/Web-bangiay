<?php

namespace App\Services;

use App\Models\Product;

class PriceService
{
    public function getPrices()
    {
        $formattedPrices = [];

        foreach (Product::PRICES as $index => $name) {
            $formattedPrices[] = [
                'name' => $name,
                'products_count' => $this->getProductCount($index),
            ];
        }

        return $formattedPrices;
    }

    private function getProductCount($index)
    {
        return Product::withFilters()
            ->when($index === 0, function ($query) {
                $query->where('price', '<', '500000');
            })
            ->when($index === 1, function ($query) {
                $query->whereBetween('price', ['500000', '1000000']);
            })
            ->when($index === 2, function ($query) {
                $query->whereBetween('price', ['1000000', '2500000']);
            })
            ->when($index === 3, function ($query) {
                $query->whereBetween('price', ['2500000', '5000000']);
            })
            ->when($index === 4, function ($query) {
                $query->whereBetween('price', ['5000000', '10000000']);
            })
            ->when($index === 5, function ($query) {
                $query->where('price', '>', '10000000');
            })
            ->count();
    }
}
