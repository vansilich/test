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
    private array $prevParams = [];

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
    public function setPrevParams(array $prevParams): ServiceFilter
    {
        $this->prevParams = $prevParams;
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
    public function rejectSomeFilters(OrderSearchFiltersState $state): void
    {}

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