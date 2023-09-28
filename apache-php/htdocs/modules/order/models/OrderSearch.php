<?php

namespace app\modules\order\models;

use app\modules\order\enums\OrderStatus;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{

    public ?string $currFilterByStatus = null;
    public array $filterByStatusVariants;

    public ?string $searchText = null;
    public int $searchCategory;
    public array $searchCategoryVariants;

    public ?string $currFilterByService = null;

    public ?string $currFilterByMode = null;

    public array $servicesByOrdersCount = [];
    public array $servicesMapById = [];
    public int $ordersOfServicesCount = 0;

    public function __construct($config = [])
    {
        $this->searchCategoryVariants = [
            0 => \Yii::t('app', 'Order ID'),
            1 => \Yii::t('app', 'Link'),
            2 => \Yii::t('app', 'Username'),
        ];
        $this->searchCategory = 0;

        $this->filterByStatusVariants = [
            ['title' => \Yii::t('app', 'All orders'), 'value' => null],
            ['title' => OrderStatus::PENDING->getText(), 'value' => OrderStatus::PENDING->value],
            ['title' => OrderStatus::IN_PROGRESS->getText(), 'value' => OrderStatus::IN_PROGRESS->value],
            ['title' => OrderStatus::COMPLETED->getText(), 'value' => OrderStatus::COMPLETED->value],
            ['title' => OrderStatus::CANCELED->getText(), 'value' => OrderStatus::CANCELED->value],
            ['title' => OrderStatus::FAIL->getText(), 'value' => OrderStatus::FAIL->value],
        ];

        parent::__construct($config);
    }

    public function getSearchState(): array
    {
        return [
            'currFilterByStatus' => $this->currFilterByStatus,
            'searchText' => $this->searchText,
            'searchCategory' => $this->searchCategory,
            'currFilterByService' => $this->currFilterByService,
            'currFilterByMode' => $this->currFilterByMode,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['searchText', 'currFilterByStatus', 'currFilterByService', 'currFilterByMode'], 'string'],
            [['searchCategory'], 'integer'],

            [['id', 'user_id', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['link'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param array $previousParams
     *
     * @return ActiveDataProvider
     */
    public function search(array $params, array $previousParams): ActiveDataProvider
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

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->from(self::tableName() . ' order')
            ->joinWith('user')
            ->joinWith('service');
        $serviceGroupQuery = clone $query;

        $this->applyFilterByStatus($query, $previousParams['OrderSearch'] ?? []);
        $this->applyFilterBySearch($query, $previousParams['OrderSearch'] ?? []);
        $this->applyFilterByService($query);
        $this->applyFilterByMode($query);

        $serviceGroupQuery->select(['services.id', 'services.name', 'COUNT(services.id) orders_cnt'])
            ->groupBy(['services.id'])
            ->orderBy(['orders_cnt' => SORT_DESC]);

        $this->applyFilterByMode($serviceGroupQuery);

        foreach ($serviceGroupQuery->asArray()->all() as $item) {
            $this->servicesByOrdersCount[] = $item;
            $this->servicesMapById[ $item['id'] ] = $item;
            $this->ordersOfServicesCount += $item['orders_cnt'];
        }

        return $dataProvider;
    }

    private function applyFilterBySearch(ActiveQuery $query, array $previousParams): void
    {
        if ($this->searchText === '' || $this->searchText === null) {
            if (isset($previousParams['searchText']) && $previousParams['searchText'] !== ''){
                $this->currFilterByMode = null;
                $this->currFilterByService = null;
            }

            $this->searchText = null;
            $this->searchCategory = 0;

            return;
        }

        if (isset($previousParams['searchText']) && $previousParams['searchText'] !== $this->searchText) {
            $this->currFilterByMode = null;
            $this->currFilterByService = null;
        }

        switch ($this->searchCategory){
            case 0:
                $this->id = $this->searchText;
                $query->andFilterWhere(['order.id' => $this->id]);
                break;
            case 1:
                $this->link = $this->searchText;
                $query->andFilterWhere(['like', 'order.link', $this->link]);
                break;
            case 2:
                $query->andWhere('CONCAT(`users`.`first_name`, \' \', `users`.`last_name`) LIKE :searchText')
                    ->addParams([':searchText' => '%' . $this->searchText . '%']);
                break;
            default:
                throw new \InvalidArgumentException("Unknown search category for filtering: '" . $this->searchCategory . "'");
        }
    }

    private function applyFilterByStatus(ActiveQuery $query, array $previousParams): void
    {
        if ($this->currFilterByStatus === '' || $this->currFilterByStatus === null) {
            if (isset($previousParams['currFilterByStatus']) && $previousParams['currFilterByStatus'] !== '') {
                $this->currFilterByMode = null;
                $this->currFilterByService = null;
            }

            $this->currFilterByStatus = null;
            return;
        }

        if (isset($previousParams['currFilterByStatus']) && $previousParams['currFilterByStatus'] !== $this->currFilterByStatus) {
            $this->currFilterByMode = null;
            $this->currFilterByService = null;
        }

        $this->status = $this->currFilterByStatus;
        $query->andFilterWhere(['order.status' => $this->status]);
    }

    private function applyFilterByService(ActiveQuery $query): void
    {
        if ($this->currFilterByService === '' || $this->currFilterByService === null) {
            $this->currFilterByService = null;
            return;
        }

        $this->service_id = $this->currFilterByService;
        $query->andFilterWhere(['order.service_id' => $this->service_id]);
    }

    private function applyFilterByMode(ActiveQuery $query): void
    {
        if ($this->currFilterByMode === '' || $this->currFilterByMode === null) {
            $this->currFilterByMode = null;
            return;
        }

        $this->mode = $this->currFilterByMode;
        $query->andFilterWhere(['order.mode' => $this->mode]);
    }

}
