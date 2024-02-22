<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Faker\Generator;

class ReportController extends Controller
{
    use ApiResponser;

    protected $product;
    protected $order;
    protected $customer;
    protected $faker;

    public function __construct(Product $product, Order $order, Customer $customer, Generator $faker)
    {
        $this->product = $product;
        $this->order = $order;
        $this->customer = $customer;
        $this->faker = $faker;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $totalProduct = $this->product->all()->count();
        $totalRevenue = $this->order->where('status', OrderStatusEnum::Received)->sum('total_price');
        $totalOrder = $this->order->all()->count();
        $totalCustomer = $this->customer->all()->count();
        $bestSellers = $this->product->bestSeller();
        $topRating = $this->product->topRating();

        $salesByMonth = [];
        $salesByMonthChartColors = [];
        $revenueByMonth = [];
        $revenueByMonthChartColors = [];

        for ($month = 1; $month <= 12; $month++) {
            $salesByMonth[] = $this->order->salesByMonth($month);
            $salesByMonthChartColors[] = $this->faker->safeHexColor();
            $revenueByMonth[] = $this->order->revenueByMonth($month);
            $revenueByMonthChartColors[] = $this->faker->safeHexColor();
        }

        $productsName = [];
        $productsRating = [];
        $topRatingChartColors = [];

        foreach ($topRating as $each) {
            $productsName[] = $each->name;
            $productsRating[] = $each->rating;
            $topRatingChartColors[] = $this->faker->safeHexColor();
        }

        $data = [
            'total_product' => $totalProduct,
            'total_revenue' => $totalRevenue,
            'total_order' => $totalOrder,
            'total_customer' => $totalCustomer,
            'sales_by_month_chart' => [
                'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'datasets' => [
                    [
                        'label' => 'Doanh số theo tháng',
                        'data' => $salesByMonth,
                        'barThickness' => 50,
                        'backgroundColor' => $salesByMonthChartColors,
                    ]
                ]
            ],
            'revenue_by_month_chart' => [
                'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'datasets' => [
                    [
                        'label' => 'Doanh thu theo tháng',
                        'data' => $revenueByMonth,
                        'barThickness' => 50,
                        'backgroundColor' => $revenueByMonthChartColors,
                    ]
                ]
            ],
            'top_rating_chart' => [
                'labels' => $productsName,
                'datasets' => [
                    [
                        'label' => 'Top Rating',
                        'data' => $productsRating,
                        'barThickness' => 50,
                        'backgroundColor' => $topRatingChartColors,
                    ]
                ]
            ],
        ];

        return $this->successReponse($data, '');
    }
}
