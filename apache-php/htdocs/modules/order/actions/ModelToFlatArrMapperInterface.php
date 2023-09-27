<?php

namespace app\modules\order\actions;

use yii\db\ActiveRecordInterface;

interface ModelToFlatArrMapperInterface
{

    /**
     * Maps ActiveRecord model (with relations) to flat array
     *
     * @param ActiveRecordInterface $model
     * @return array
     */
    public function __invoke(ActiveRecordInterface $model): array;

}