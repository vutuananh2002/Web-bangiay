<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class ProductTypeEnum extends Enum implements LocalizedEnum
{
    public const Man = 0;
    public const Women = 1;
    public const Child = 2;
    
}
