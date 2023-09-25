<?php

use app\modules\order\models\Order;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var \app\modules\order\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table order-table'
        ],
        'dataProvider' => $dataProvider,
        'layout' => "{errors}\n{items}\n{pager}\n{summary}",
        'columns' => [
            'id',
            'user_id',
            'link',
            'quantity',
            [
                'header' => $this->render('/widgets/GridView/_header-sort-column', [
                    'dataProvider' => $dataProvider
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function ($model, $key, $index, $column){
                    return $model->status;
                },
            ],
            'status',
            [
                'header' => $this->render('/widgets/GridView/_header-sort-column', [
                    'dataProvider' => $dataProvider
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function ($model, $key, $index, $column){
                    return $model->service_id   ;
                },
            ],
            'created_at',
        ],
    ]); ?>


</div>
