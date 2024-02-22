<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\RatingService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    use ApiResponser;

    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function __invoke()
    {
        $rates = $this->ratingService->getRates();
        $message = 'Lấy thành công danh sách rating';

        return $this->successReponse($rates, $message);
    }
}
