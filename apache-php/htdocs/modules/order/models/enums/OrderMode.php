<?php

namespace order\models\enums;

use app\utils\traits\EnumToArray;
use InvalidArgumentException;
use Yii;

/**
 * All enumerations of order`s `mode` status
 */
enum OrderMode: int
{
    use EnumToArray;

    case MANUAL = 0;
    case AUTO = 1;

    /**
     * @param int $mode
     * @return self
     * @throws InvalidArgumentException
     */
    public static function matchFromInt(int $mode): self
    {
        return match($mode) {
            OrderMode::MANUAL->value => OrderMode::MANUAL,
            OrderMode::AUTO->value => OrderMode::AUTO,
            default => throw new InvalidArgumentException('unknown mode for this param')
        };
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return match($this) {
            OrderMode::MANUAL => Yii::t('app', 'manual'),
            OrderMode::AUTO  => Yii::t('app', 'auto'),
        };
    }
}
