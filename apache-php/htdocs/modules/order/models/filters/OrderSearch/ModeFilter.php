<?php

namespace order\models\filters\OrderSearch;

use order\models\OrderSearch;
use order\models\OrderSearchFiltersState;
use yii\db\ActiveQuery;

/**
 * Filter by modification (`mode` column)
 */
class ModeFilter implements FilterInterface
{

    private bool $wasModified = false;

    private array $params;

    /**
     * @inheritDoc
     */
    public function setParams(array $params): ModeFilter
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function modifyState(OrderSearchFiltersState $state): void
    {
        if (!isset($this->params['byMode']) || $this->params['byMode'] === '') {
            return;
        }

        $state->setByMode($this->params['byMode']);
    }

    /**
     * @inheritDoc
     */
    public function apply(ActiveQuery $query, OrderSearchFiltersState $state): void
    {
        $query->andFilterWhere(['order.mode' => $state->byMode]);
    }

    /**
     * @inheritDoc
     */
    public function applyToServiceState(ActiveQuery $query, OrderSearchFiltersState $state): void
    {
        $this->apply($query, $state);
    }

}