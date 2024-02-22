<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AccountStatusEnum extends Enum
{
    public const NotActive = 0;
    public const Active = 1;
    public const Block = 2;
}
