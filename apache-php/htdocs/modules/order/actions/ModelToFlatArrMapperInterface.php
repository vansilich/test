<?php

namespace app\modules\order\actions;

use yii\db\ActiveRecordInterface;

interface ModelToFlatArrMapperInterface
{

    /**
     * Maps ActiveRecord model (with relations) to flat array (elements are not sub arrays)
     *
     * @param ActiveRecordInterface $model
     * @return array
     */
    public function __invoke(ActiveRecordInterface $model): array;

}