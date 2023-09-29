<?php

namespace order\controllers\services;

use yii\db\ActiveRecordInterface;

/**
 * Enable map model to flat array
 */
interface ModelToFlatArrMapperInterface
{
    /**
     * Maps ActiveRecord model (with relations or not) to flat array (return elements don`t have sub arrays)
     *
     * @param ActiveRecordInterface $model
     * @return array
     */
    public function __invoke(ActiveRecordInterface $model): array;

}
