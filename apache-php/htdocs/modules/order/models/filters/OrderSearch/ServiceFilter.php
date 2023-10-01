<?php

namespace order\models\filters\OrderSearch;

use order\models\OrderSearchFiltersState;
use yii\db\ActiveQuery;

/**
 * Filter by service (`service_id` column)
 */
class ServiceFilter implements FilterInterface
{

    private array $params;

    /**
     * @inheritDoc
     */
    public function setParams(array $params): ServiceFilter
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function modifyState(OrderSearchFiltersState $state): void
    {
        if (!isset($this->params['byService']) || $this->params['byService'] === '') {
            return;
        }

        $state->setByService($this->params['byService']);
    }

    /**
     * @inheritDoc
     */
    public function apply(ActiveQuery $query, OrderSearchFiltersState $state): void
    {
        $query->andFilterWhere(['order.service_id' => $state->byService]);
    }

    /**
     * @inheritDoc
     */
    public function applyToServiceState(ActiveQuery $query, OrderSearchFiltersState $state): void
    {}

}