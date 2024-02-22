<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\PriceService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    use ApiResponser;

    protected $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function index(Request $request)
    {
        $prices = $this->priceService->getPrices();
        $message = 'Lấy thành công bảng giá sản phẩm.';

        return $this->successReponse($prices, $message);
    }
}
