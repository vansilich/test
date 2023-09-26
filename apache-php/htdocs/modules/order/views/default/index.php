<?php

use app\modules\order\enums\OrderMode;
use app\modules\order\enums\OrderStatus;
use app\modules\order\models\Order;
use app\modules\order\models\OrderSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    let url = window.location.href;
    let [path, params] = url.split("?");
    let result = path + '?' + new URLSearchParams(Object.fromEntries(new URLSearchParams(params))).toString();

    window.history.replaceState(null, document.title, result);
</script>

<div class="order-index">

    <ul class="nav nav-tabs p-b">
        <?php
            foreach ($searchModel->filterByStatusVariants as $item) {
                $status = $item['title'];
                $status = mb_ucfirst($status, mb_detect_encoding($status));
        ?>
            <li class="<?= $item['value'] === $searchModel->currFilterByStatus ? 'active' : '' ?>">
                <a href="">
                    <?php $form = ActiveForm::begin(['method' => 'get']) ?>
                        <?= Html::hiddenInput('OrderSearch[currFilterByStatus]', $item['value']) ?>
                        <?= Html::submitButton($status, ['style' => 'border: none; background: none']) ?>
                    <?php ActiveForm::end(); ?>
                </a>
            </li>
        <?php } ?>

        <li class="pull-right custom-search">
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form-inline'], 'method' => 'get']) ?>

                <div class="input-group">
                    <?= Html::textInput('OrderSearch[searchText]', $searchModel->searchText, [
                        'class' => 'form-control',
                        'placeholder' => Yii::t('app', 'Search orders')
                    ]) ?>

                    <span class="input-group-btn search-select-wrap">
                        <?= Html::dropDownList('OrderSearch[searchCategory]', 0, $searchModel->searchCategoryVariants, [
                            'class' => 'form-control search-select'
                        ]) ?>

                        <?= Html::submitButton('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', [
                                'class' => 'btn btn-default'
                        ])  ?>
                    </span>
                </div>
            <?php ActiveForm::end() ?>
        </li>
    </ul>

    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table order-table'
        ],
        'summary' => sprintf('{begin} %s {end} %s {totalCount}', Yii::t('app', 'to'), Yii::t('app', 'of')),
        'summaryOptions' => ['class' => 'col-sm-4 pagination-counters'],
        'dataProvider' => $dataProvider,
        'layout' => "{errors}\n{items}\n<div class=\"col-sm-8\"><nav>{pager}</nav></div>\n{summary}",
        'columns' => [
            'id',
            [
                'header' => Yii::t('app', 'User'),
                'content' => function (Order $model, $key, $index, $column){
                    return $model->user->first_name . ' ' . $model->user->last_name;
                }
            ],
            'link',
            'quantity',
            [
                'header' => $this->render('/widgets/GridView/_header-sort-column', [
                    'dataProvider' => $dataProvider,
                    'title' => Yii::t('app', 'Service'),
                    'content' => (function() use ($searchModel, $dataProvider): array {
                        $result = [];
                        $result[] = '<a href="">' . Yii::t('app', 'All') . ' (' . $dataProvider->getTotalCount() . ')</a>';
                        foreach ($searchModel->serviceWithOrdersCnt as $service) {
                            $result[] = '
                                <a href=""><span class="label-id">' . Html::encode($service['orders_cnt']) . '</span> '. Html::encode($service['name']) . '</a>
                            ';
                        }
                        return $result;
                    })()
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function (Order $model, $key, $index, $column){
                    return $model->service->name;
                },
            ],
            [
                'header' => Yii::t('app', 'Status'),
                'content' => function(Order $model, $key, $index, $column){
                    $statusText = OrderStatus::matchFromInt($model->status)->getText();
                    return mb_ucfirst($statusText, mb_detect_encoding($statusText));
                }
            ],
            [
                'header' => $this->render('/widgets/GridView/_header-sort-column', [
                    'dataProvider' => $dataProvider,
                    'title' => Yii::t('app', 'Mode'),
                    'content' => (function(): array {
                        $result = ['<a href="">' . Yii::t('app', 'All') . '</a>'];

                        foreach (OrderMode::values() as $value) {
                            $textValue = OrderMode::matchFromInt($value)->getText();
                            $result[] = '<a href="">' . mb_ucfirst($textValue, mb_detect_encoding($textValue)) . '</a>';
                        }
                        return $result;
                    })(),
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function (Order $model, $key, $index, $column){
                    $modeText = OrderMode::matchFromInt($model->mode)->getText();
                    return mb_ucfirst($modeText, mb_detect_encoding($modeText));
                },
            ],
            [
                'header' => Yii::t('app', 'Created'),
                'content' => function (Order $model, $key, $index, $column){
                    $date = Yii::$app->formatter->asDate($model->created_at, 'Y-mm-dd');
                    $time = Yii::$app->formatter->asDate($model->created_at, 'H:i:s');
                    return "<span class=\"nowrap\">$date</span>\n<span class=\"nowrap\">$time</span>";
                }
            ],
        ],
    ]); ?>

</div>
