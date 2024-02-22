<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatusEnum extends Enum implements LocalizedEnum
{
    const Pending = 0;
    const Delivering = 1;
    const Delivered = 2;
    const Received = 3;
    const Cancelled = 4;
}
