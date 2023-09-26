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

    public ?string $searchText = null;

    public ?string $currFilterByStatus = null;
    public array $filterByStatusVariants;

    public int $searchCategory;
    public array $searchCategoryVariants;

    public array $serviceWithOrdersCnt = [];

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['searchText', 'currFilterByStatus'], 'string'],
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

        // add conditions that should always apply here

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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->from(self::tableName() . ' order')
            ->joinWith('user')
            ->joinWith('service');

        $this->setupStatusFilter($query, $previousParams['OrderSearch'] ?? []);
        $this->setupSearchFilter($query);

//        dd($query->createCommand()->rawSql);

        $serviceGroupQuery = clone $query;
        $serviceGroupQuery->select(['services.id', 'services.name', 'COUNT(services.id) orders_cnt'])
            ->groupBy(['services.id']);

        $this->serviceWithOrdersCnt = $serviceGroupQuery->asArray()->all();

        return $dataProvider;
    }

    private function setupSearchFilter(ActiveQuery $query): void
    {
        if ($this->searchText === '' || $this->searchText === null) {
            $this->searchText = null;
            return;
        }

        // TODO сбросить все остальные фильтры

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

    private function setupStatusFilter(ActiveQuery $query, array $previousParams): void
    {
        if ($this->currFilterByStatus === null || $this->currFilterByStatus === '') {
            return;
        }

        $previousParams['currFilterByStatus'] = $previousParams['currFilterByStatus'] ?? null;

        // if changed from last call
        if ($this->currFilterByStatus !== $previousParams['currFilterByStatus']) {

        }

        $this->status = $this->currFilterByStatus;
        $query->andFilterWhere(['order.status' => $this->status]);
    }

}
