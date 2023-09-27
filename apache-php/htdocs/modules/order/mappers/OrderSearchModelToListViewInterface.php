<?php

namespace app\modules\order\mappers;

use app\modules\order\actions\ModelToFlatArrMapperInterface;
use app\modules\order\enums\OrderMode;
use app\modules\order\enums\OrderStatus;
use app\modules\order\models\OrderSearch;
use Yii;
use yii\base\InvalidConfigException;

class OrderSearchModelToListViewInterface implements ModelToFlatArrMapperInterface
{

    /**
     * @throws InvalidConfigException
     */
    public function __invoke(OrderSearch|\yii\db\ActiveRecordInterface $model): array
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