<?php

namespace order\models\enums;

use app\utils\traits\EnumToArray;
use InvalidArgumentException;
use Yii;

/**
 * All enumerations of order`s `status`
 */
enum OrderStatus: int
{
    use EnumToArray;

    case PENDING = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
    case CANCELED = 3;
    case FAIL = 4;

    /**
     * @param int $status
     * @return self
     * @throws InvalidArgumentException
     */
    public static function matchFromInt(int $status): self
    {
        return match($status) {
            OrderStatus::PENDING->value => OrderStatus::PENDING,
            OrderStatus::IN_PROGRESS->value => OrderStatus::IN_PROGRESS,
            OrderStatus::COMPLETED->value => OrderStatus::COMPLETED,
            OrderStatus::CANCELED->value => OrderStatus::CANCELED,
            OrderStatus::FAIL->value => OrderStatus::FAIL,
            default => throw new InvalidArgumentException('unknown status for this param')
        };
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return match($this) {
            OrderStatus::PENDING => Yii::t('app', 'pending'),
            OrderStatus::IN_PROGRESS => Yii::t('app', 'in progress'),
            OrderStatus::COMPLETED => Yii::t('app', 'completed'),
            OrderStatus::CANCELED => Yii::t('app', 'canceled'),
            OrderStatus::FAIL => Yii::t('app', 'fail'),
        };
    }

    public function getUrlSafeText(): string
    {
        return match($this) {
            OrderStatus::PENDING => Yii::t('app', 'pending'),
            OrderStatus::IN_PROGRESS => Yii::t('app', 'in_progress'),
            OrderStatus::COMPLETED => Yii::t('app', 'completed'),
            OrderStatus::CANCELED => Yii::t('app', 'canceled'),
            OrderStatus::FAIL => Yii::t('app', 'fail'),
        };
    }
}
