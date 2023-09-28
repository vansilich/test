<?php

namespace app\modules\order\controllers;

use app\modules\order\actions\ExportToCsv;
use app\modules\order\mappers\OrderSearchModelToListView;
use app\modules\order\models\OrderSearch;
use yii\base\Event;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `order` module
 */
class ListController extends Controller
{
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
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $this->getPrevParams());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Download CSV file with contents of @see self::actionIndex method
     *
     * @return Response
     */
    public function actionAsCsv(): Response
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, []);

        $csvPath = (new ExportToCsv())->export($dataProvider, new OrderSearchModelToListView());

        Event::on(Response::class, Response::EVENT_AFTER_SEND, function ($event) use ($csvPath) {
            unlink($csvPath);
        });

        return \Yii::$app->response->sendFile($csvPath, 'order.scv');
    }

    /**
     * Query params from `Referrer` HTTP header if request comes from same page
     *
     * @return array
     */
    private function getPrevParams(): array
    {
        $prevParams = [];
        if(\Yii::$app->request->referrer !== null){
            $prevParsed = parse_url(\Yii::$app->request->referrer);
            $prevCanonical = sprintf('http://%s%s', $prevParsed['host'], $prevParsed['path']);

            // So if we come from same page
            if ($prevCanonical === Url::canonical() && isset($prevParsed['query'])) {
                parse_str($prevParsed['query'], $prevParams);
            }
        }

        return $prevParams;
    }
}
