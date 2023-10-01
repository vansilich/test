<?php

namespace order\models;

use app\modules\order\models\ActiveRecord\Order;
use order\models\filters\OrderSearch\FilterInterface;
use order\models\filters\OrderSearch\ModeFilter;
use order\models\filters\OrderSearch\SearchFilter;
use order\models\filters\OrderSearch\ServiceFilter;
use order\models\filters\OrderSearch\StatusFilter;
use yii\caching\CacheInterface;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
    private CacheInterface $cache;

    /**
     * All filters ids
     */
    const BY_STATUS = 0;
    const BY_SEARCH = 1;
    const BY_MODE = 2;
    const BY_SERVICE = 3;

    /**
     * @var array<int, FilterInterface> - all filters that will be applied
     */
    public array $filters;

    /**
     * @var OrderSearchFiltersState - state of all filters
     */
    public OrderSearchFiltersState $filtersState;

    /**
     * @var array[] - ordered by orders count services
     */
    public array $servicesByOrdersCount = [];
    /**
     * @var array<int, array> - same as $this->servicesByOrdersCount, but keys are ids of services
     */
    public array $servicesMapById = [];
    /**
     * @var int - count of all orders in all services
     */
    public int $ordersOfServicesCount = 0;

    /**
     * @var array - data of current request of OrderSearchFiltersState::class values
     */
    public array $params;

    public function __construct(CacheInterface $cache, $config = [])
    {
        $this->cache = $cache;

        $this->filters = [
            static::BY_STATUS   => new StatusFilter(),
            static::BY_SEARCH   => new SearchFilter(),
            static::BY_MODE     => new ModeFilter(),
            static::BY_SERVICE  => new ServiceFilter(),
        ];

        parent::__construct($config);
    }

    public function setParams(array $params): OrderSearch
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Getter for filters current state
     *
     * @return OrderSearchFiltersState
     */
    public function getFiltersState(): OrderSearchFiltersState
    {
        return $this->filtersState;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->filtersState = new OrderSearchFiltersState();
        foreach ($this->filters as $filter) {

            $filter->setParams($this->params);
            $filter->modifyState($this->filtersState);
        }

        // grid filtering conditions
        $query->from(self::tableName() . ' order')
            ->joinWith('user')
            ->joinWith('service');
        $serviceGroupQuery = clone $query;

        foreach ($this->filters as $filter) {
            $filter->apply($query, $this->filtersState);
        }

        $this->setServicesInfo($serviceGroupQuery);

        return $dataProvider;
    }

    /**
     * Set info about services state
     *
     * @param ActiveQuery $serviceGroupQuery
     * @return void
     */
    private function setServicesInfo(ActiveQuery $serviceGroupQuery): void
    {
        $serviceGroupQuery->select(['services.id', 'services.name', 'COUNT(services.id) orders_cnt'])
            ->groupBy(['services.id'])
            ->orderBy(['orders_cnt' => SORT_DESC]);

        foreach ($this->filters as $filter) {
            $filter->applyToServiceState($serviceGroupQuery, $this->filtersState);
        }

        $serviceGroupQuerySql = $serviceGroupQuery->createCommand()->rawSql;
        $serviceGroup = $this->cache->get($serviceGroupQuerySql);

        if ($serviceGroup === false) {
            $serviceGroup = $serviceGroupQuery->asArray()->all();
            $this->cache->set($serviceGroupQuerySql, $serviceGroup, 1000);
        }

        foreach ($serviceGroup as $item) {
            $this->servicesByOrdersCount[] = $item;
            $this->servicesMapById[ $item['id'] ] = $item;
            $this->ordersOfServicesCount += $item['orders_cnt'];
        }
    }

}
