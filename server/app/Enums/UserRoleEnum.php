<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRoleEnum extends Enum
{
    public const BaseUser = 0;
    public const Moderator = 1;
    public const Administrator = 2;
    public const SuperAdministrator = 3;
}
