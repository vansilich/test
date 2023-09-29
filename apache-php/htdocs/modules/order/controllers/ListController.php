<?php

namespace order\controllers;

use order\controllers\services\ExportToCsv;
use order\models\enums\OrderStatus;
use order\models\mappers\OrderSearchModelToListView;
use order\models\OrderSearch;
use order\models\OrderSearchFiltersState;
use Yii;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

/**
 * Default controller for the `order` module
 */
class ListController extends Controller
{
    public $defaultAction = 'index';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Order models
     *
     * @param CacheInterface $cache
     * @return string
     * @throws InvalidConfigException
     */
    public function actionIndex(CacheInterface $cache, ?string $status = null): string
    {
        $searchModel = new OrderSearch($cache);

        $formName = (new OrderSearchFiltersState)->formName(); // filter state object

        $params = $this->request->get($formName) ?? [];
        if ($status !== null && $status !== '') {
            foreach (OrderStatus::cases() as $case) {

                if ($case->getUrlSafeText() === $status) {
                    $params['byStatus'] = $case->value;
                }
            }
        }
        $searchModel->setParams($params);

        $prevParams = [];
        // parse query params if request comes from the same page
        $referrer = Yii::$app->request->referrer;
        if ($referrer !== null) {
            $prevParsed = parse_url($referrer);
            if (isRequestFromSameCanonical(Yii::$app->request->absoluteUrl, $referrer) && isset($prevParsed['query'])) {
                parse_str($prevParsed['query'], $prevParams);
            }
        }
        $searchModel->setPrevParams($prevParams[$formName] ?? []);

        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Download CSV file with contents of index action
     * @see self::actionIndex
     *
     * @param CacheInterface $cache
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionAsCsv(CacheInterface $cache): Response
    {
        $searchModel = new OrderSearch($cache);

        $formName = (new OrderSearchFiltersState)->formName(); // filter state object

        $searchModel->setParams($this->request->post($formName) ?? []);
        $dataProvider = $searchModel->search();

        $csvPath = (new ExportToCsv())->export($dataProvider, new OrderSearchModelToListView());

        Event::on(Response::class, Response::EVENT_AFTER_SEND, function () use ($csvPath) {
            unlink($csvPath);
        });

        return Yii::$app->response->sendFile($csvPath, 'order.scv');
    }

}
