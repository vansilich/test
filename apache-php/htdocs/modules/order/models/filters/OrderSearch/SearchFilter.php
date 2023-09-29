<?php

namespace order\models\filters\OrderSearch;

use InvalidArgumentException;
use order\models\OrderSearchFiltersState;
use Yii;
use yii\db\ActiveQuery;

/**
 * Filter by searching values
 */
class SearchFilter implements FilterInterface
{

    const BY_ORDER_ID = 0;
    const BY_LINK = 1;
    const BY_USERNAME = 2;

    private array $categories;

    private array $params;
    private array $prevParams = [];

    public function __construct()
    {
        $this->categories = [
            static::BY_ORDER_ID => Yii::t('app', 'Order ID'),
            static::BY_LINK     => Yii::t('app', 'Link'),
            static::BY_USERNAME => Yii::t('app', 'Username'),
        ];
    }

    /**
     * Returns current filter available categories of searching
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @inheritDoc
     */
    function setParams(array $params): SearchFilter
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPrevParams(array $prevParams): SearchFilter
    {
        $this->prevParams = $prevParams;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function modifyState(OrderSearchFiltersState $state): void
    {
        if (!isset($this->params['searchText']) || $this->params['searchText'] === '') {
            return;
        }

        $state->setSearchText($this->params['searchText']);

        if (!isset($this->params['searchCategory'])) {
            $state->setSearchCategory(static::BY_ORDER_ID);
            return;
        }

        if (!array_key_exists((int)$this->params['searchCategory'], $this->categories)) {
            throw new InvalidArgumentException(sprintf("Unknown category: '%s'", $this->params['searchCategory']));
        }

        $state->setSearchCategory($this->params['searchCategory']);
    }

    /**
     * Reject Service and Mode filters if current filter changed
     *
     * @inheritDoc
     */
    public function rejectSomeFilters(OrderSearchFiltersState $state): void
    {
        if (empty($this->prevParams)){
            return;
        }

        if ($state->searchText === null || $state->searchText === '') {
            if (isset($this->prevParams['searchText']) && $this->prevParams['searchText'] !== '') {
                $state->setByService(null);
                $state->setByMode(null);
            }
            return;
        }

        if (!isset($this->prevParams['searchText']) || $this->prevParams['searchText'] !== $state->searchText) {
            $state->setByService(null);
            $state->setByMode(null);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function apply(ActiveQuery $query, OrderSearchFiltersState $state): void
    {
        switch ($state->searchCategory) {
            case self::BY_ORDER_ID:
                $query->andFilterWhere(['order.id' => $state->searchText]);
                break;
            case self::BY_LINK:
                $query->andFilterWhere(['like', 'order.link', $state->searchText]);
                break;
            case self::BY_USERNAME:
                $query->andWhere('CONCAT(`users`.`first_name`, \' \', `users`.`last_name`) LIKE :searchText')
                    ->addParams([':searchText' => '%' . $state->searchText . '%']);
                break;
            default:
                throw new InvalidArgumentException("Unknown search category for filtering: '" . $state->searchCategory . "'");
        }
    }

    /**
     * @inheritDoc
     */
    public function applyToServiceState(ActiveQuery $query, OrderSearchFiltersState $state): void
    {}

}