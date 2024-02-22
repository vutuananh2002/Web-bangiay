<?php declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Enums\ProductTypeEnum;

return [
    ProductTypeEnum::class => [
        ProductTypeEnum::Man => 'Nam',
        ProductTypeEnum::Women => 'Nữ',
        ProductTypeEnum::Child => 'Trẻ em',
    ],
    OrderStatusEnum::class => [
        OrderStatusEnum::Pending => 'Chờ duyệt',
        OrderStatusEnum::Delivering => 'Đang giao',
        OrderStatusEnum::Delivered => 'Đã giao',
        OrderStatusEnum::Received => 'Đã nhận',
        OrderStatusEnum::Cancelled => 'Đã hủy',
    ]
];