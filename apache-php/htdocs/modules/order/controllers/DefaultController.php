<?php

namespace app\modules\order\controllers;

use app\modules\order\models\Order;
use app\modules\order\models\OrderSearch;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `order` module
 */
class DefaultController extends Controller
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
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();

        $prevParams = [];
        if(\Yii::$app->request->referrer !== null){
            $prevParsed = parse_url(\Yii::$app->request->referrer);
            $prevCanonical = sprintf('http://%s%s', $prevParsed['host'], $prevParsed['path']);

            // So if we come from same page
            if (isset($prevParsed['query']) && $prevCanonical === Url::canonical()) {
                parse_str($prevParsed['query'], $prevParams);
            }
        }

        $dataProvider = $searchModel->search($this->request->queryParams, $prevParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
