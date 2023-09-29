<?php

namespace order\models\mappers;

use order\controllers\services\ModelToFlatArrMapperInterface;
use order\models\enums\OrderMode;
use order\models\enums\OrderStatus;
use order\models\OrderSearch;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;

/**
 * Maps OrderSearch map to flat array
 */
class OrderSearchModelToListView implements ModelToFlatArrMapperInterface
{
    /**
     * @inheritDoc
     *
     * @throws InvalidConfigException
     */
    public function __invoke(OrderSearch|ActiveRecordInterface $model): array
    {
        $statusText = OrderStatus::matchFromInt($model->status)->getText();
        $statusText = mb_ucfirst($statusText, mb_detect_encoding($statusText));

        $modeText = OrderMode::matchFromInt($model->mode)->getText();
        $modeText = mb_ucfirst($modeText, mb_detect_encoding($modeText));

        $datetime = Yii::$app->formatter->asDate($model->created_at, 'php:Y-m-d H:i:s');

        return [
            'ID' => $model->id,
            'User' => $model->user->first_name . ' ' . $model->user->last_name,
            'Link' => $model->link,
            'Quantity' => $model->quantity,
            'Service' => $model->service->name,
            'Status' => $statusText,
            'Mode' => $modeText,
            'Created' => $datetime
        ];
    }

}
