<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ApiResponser;

    protected $product;
    protected $review;

    public function __construct(Product $product, Review $review)
    {
        $this->product = $product;
        $this->review = $review;
    }


    public function index(Request $request)
    {
        $newArrivals = $this->product->newArrival();
        $bestSellers = $this->product->bestSeller();
        $topRating = $this->product->topRating();
        $topReviews = $this->review->topReview();

        $responseData = [
            'new_arrivals' => [
                'title' => 'New Arrivals',
                'items' => ProductResource::collection($newArrivals), 
            ],
            'best_sellers' => [
                'title' => 'Best Sellers',
                'items' => ProductResource::collection($bestSellers),
            ],
            'top_rating' => [
                'title' => 'Top Rating',
                'items' => ProductResource::collection($topRating),
            ],
            'top_reviews' => [
                'title' => 'Customer Feedback',
                'items' => ReviewResource::collection($topReviews),
            ],
        ];

        $message = 'Success';

        return $this->successReponse($responseData, $message);
    }
}
