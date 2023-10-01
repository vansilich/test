<?php

namespace order\models\filters\OrderSearch;

use order\models\enums\OrderStatus;
use order\models\OrderSearchFiltersState;
use Yii;
use yii\db\ActiveQuery;

/**
 * Filter by status (`status` column)
 */
class StatusFilter implements FilterInterface
{
    private array $variants;

    private array $params;

    public function __construct()
    {
        $this->variants = [['title' => Yii::t('app', 'All orders'), 'value' => null]];

        foreach (OrderStatus::cases() as $case) {
            $this->variants[] = [
                'title' => mb_ucfirst(Yii::t('app', $case->getText()), mb_detect_encoding('utf-8')),
                'value' => $case->getUrlSafeText()
            ];
        }
    }

    /**
     * Returns current filter`s variants
     *
     * @return array
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params): StatusFilter
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function modifyState(OrderSearchFiltersState $state): void
    {
        if (!isset($this->params['byStatus']) || $this->params['byStatus'] === '') {
            return;
        }

        $state->setByStatus($this->params['byStatus']);
    }

    /**
     * @inheritDoc
     */
    public function apply(ActiveQuery $query, OrderSearchFiltersState $state): void
    {
        $query->andFilterWhere(['order.status' => $state->byStatus]);
    }

    /**
     * @inheritDoc
     */
    public function applyToServiceState(ActiveQuery $query, OrderSearchFiltersState $state): void
    {}

}