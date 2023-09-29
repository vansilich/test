<?php

namespace order\models;

use order\models\filters\OrderSearch\SearchFilter;
use yii\base\Model;

/**
 * DTO for storing filter state
 */
class OrderSearchFiltersState extends Model
{

    /**
     * @var string|null - filter value for filtering by status
     */
    public ?string $byStatus = null;

    /**
     * @var string|null - text of searching
     */
    public ?string $searchText = null;
    /**
     * @var int - category for searching
     */
    public int $searchCategory = SearchFilter::BY_ORDER_ID;

    /**
     * @var string|null - filter value for filtering by service (service_id)
     */
    public ?string $byService = null;

    /**
     * @var string|null - filter value for filtering by modification
     */
    public ?string $byMode = null;

    /**
     * @return string|null
     */
    public function getByStatus(): ?string
    {
        return $this->byStatus;
    }

    /**
     * @param string|null $byStatus
     * @return $this
     */
    public function setByStatus(?string $byStatus): OrderSearchFiltersState
    {
        $this->byStatus = $byStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    /**
     * @param string|null $searchText
     * @return $this
     */
    public function setSearchText(?string $searchText): OrderSearchFiltersState
    {
        $this->searchText = $searchText;
        return $this;
    }

    /**
     * @return int
     */
    public function getSearchCategory(): int
    {
        return $this->searchCategory;
    }

    /**
     * @param int $searchCategory
     * @return $this
     */
    public function setSearchCategory(int $searchCategory): OrderSearchFiltersState
    {
        $this->searchCategory = $searchCategory;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getByService(): ?string
    {
        return $this->byService;
    }

    /**
     * @param string|null $byService
     * @return $this
     */
    public function setByService(?string $byService): OrderSearchFiltersState
    {
        $this->byService = $byService;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getByMode(): ?string
    {
        return $this->byMode;
    }

    /**
     * @param string|null $byMode
     * @return $this
     */
    public function setByMode(?string $byMode): OrderSearchFiltersState
    {
        $this->byMode = $byMode;
        return $this;
    }

}