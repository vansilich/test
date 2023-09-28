<?php

namespace app\modules\order\enums;

use app\utils\traits\EnumToArray;
use InvalidArgumentException;
use Yii;

enum OrderStatus: int
{
    use EnumToArray;

    case PENDING = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
    case CANCELED = 3;
    case FAIL = 4;

    public static function ToAssocArr(): array
    {
        return [
            Yii::t('app', 'pending') => OrderStatus::PENDING,
            Yii::t('app', 'in progress') => OrderStatus::IN_PROGRESS,
            Yii::t('app', 'completed') => OrderStatus::COMPLETED,
            Yii::t('app', 'canceled') => OrderStatus::CANCELED,
            Yii::t('app', 'fail') => OrderStatus::FAIL,
        ];
    }

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
    public function getText(): string
    {
        return match($this) {
            OrderStatus::PENDING => Yii::t('app', 'pending'),
            OrderStatus::IN_PROGRESS  => Yii::t('app', 'in progress'),
            OrderStatus::COMPLETED => Yii::t('app', 'completed'),
            OrderStatus::CANCELED => Yii::t('app', 'canceled'),
            OrderStatus::FAIL => Yii::t('app', 'fail'),
        };
    }
}
