<?php

namespace order\models\filters\OrderSearch;

use order\models\OrderSearchFiltersState;
use yii\db\ActiveQuery;

/**
 * Interface for filters of OrderSearch model
 */
interface FilterInterface
{

    /**
     * Set filter query params to this filter state
     *
     * @param array<string, mixed> $params
     * @return FilterInterface
     */
    public function setParams(array $params): FilterInterface;

    /**
     * Set params from current filter to global filters $state
     *
     * @param OrderSearchFiltersState $state
     * @return void
     */
    public function modifyState(OrderSearchFiltersState $state): void;

    /**
     * Apply params from current filter to main SQL search statement
     *
     * @param ActiveQuery $query
     * @param OrderSearchFiltersState $state
     * @return void
     */
    public function apply(ActiveQuery $query, OrderSearchFiltersState $state): void;

    /**
     * Apply params from current filter to SQL search statement of services dropdown filter
     *
     * @param ActiveQuery $query
     * @param OrderSearchFiltersState $state
     * @return void
     */
    public function applyToServiceState(ActiveQuery $query, OrderSearchFiltersState $state): void;
}