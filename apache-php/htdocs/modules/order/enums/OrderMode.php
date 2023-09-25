<?php

namespace app\modules\order\enums;

use app\utils\helpers\traits\EnumToArray;

enum OrderMode: int
{
    use EnumToArray;

    case MANUAL = 0;
    case AUTO = 1;
}