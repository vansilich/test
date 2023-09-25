<?php

namespace app\modules\order\enums;

use app\utils\helpers\traits\EnumToArray;

enum OrderStatus: int
{
    use EnumToArray;

    case PENDING = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
    case CANCELED = 3;
    case FAIL = 4;
}