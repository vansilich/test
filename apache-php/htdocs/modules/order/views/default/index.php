<?php

use app\modules\order\enums\OrderMode;
use app\modules\order\enums\OrderStatus;
use app\modules\order\models\Order;
use app\modules\order\models\OrderSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    // cleanup duplicates in query params without reloading page
    let url = window.location.href;
    let [path, params] = url.split("?");
    let result = path + '?<?= http_build_query(['OrderSearch' => $searchModel->getSearchState()]); ?>';

    window.history.replaceState(null, document.title, result);
</script>

<div class="order-index">

    <ul class="nav nav-tabs p-b">
        <?php
            foreach ($searchModel->filterByStatusVariants as $item) {
                $status = $item['title'];
                $status = mb_ucfirst($status, mb_detect_encoding($status));
        ?>
            <li class="<?= (string)$item['value'] === (string)$searchModel->currFilterByStatus ? 'active' : '' ?>">
                <a href="">
                    <form action="/<?= Yii::$app->controller->action->uniqueId ?>" method="get">
                        <?php
                            $state = $searchModel->getSearchState();
                            $state['currFilterByStatus'] = $item['value'];
                            foreach ($state as $key => $value) :
                        ?>
                            <?= Html::hiddenInput('OrderSearch[' . $key . ']', $value) ?>
                        <?php endforeach; ?>
                        <?= Html::submitButton($status, ['style' => 'border: none; background: none']) ?>
                    </form>
                </a>
            </li>
        <?php } ?>

        <li class="pull-right custom-search">
            <form action="/<?= Yii::$app->controller->action->uniqueId ?>" method="get" class="form-inline">
                <?php
                    $state = $searchModel->getSearchState();
                    unset($state['searchText']);
                    unset($state['searchCategory']);
                    foreach ($state as $key => $value) :
                ?>
                    <?= Html::hiddenInput('OrderSearch[' . $key . ']', $value) ?>
                <?php endforeach; ?>

                <div class="input-group">
                    <?= Html::textInput('OrderSearch[searchText]', $searchModel->searchText, [
                        'class' => 'form-control',
                        'placeholder' => Yii::t('app', 'Search orders')
                    ]) ?>

                    <span class="input-group-btn search-select-wrap">
                        <?= Html::dropDownList('OrderSearch[searchCategory]', $searchModel->searchCategory, $searchModel->searchCategoryVariants, [
                            'class' => 'form-control search-select'
                        ]) ?>

                        <?= Html::submitButton('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', [
                                'class' => 'btn btn-default'
                        ])  ?>
                    </span>
                </div>
            </form>
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
                'header' => $this->render('_header-sort-column', [
                    'dataProvider' => $dataProvider,
                    'title' => Yii::t('app', 'Service'),
                    'itemView' => $this->render('_dropdown-sort-by-service', [
                        'searchModel' => $searchModel
                    ]),
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function (Order $model, $key, $index, $column) use ($searchModel) {
                    return '<span class="label-id">' .
                        $searchModel->servicesMapById[$model->service_id]['orders_cnt'] .
                        '</span>' .
                        $model->service->name;
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
                'header' => $this->render('_header-sort-column', [
                    'dataProvider' => $dataProvider,
                    'title' => Yii::t('app', 'Mode'),
                    'itemView' => $this->render('_dropdown-sort-by-mode', [
                        'searchModel' => $searchModel
                    ]),
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
                    $date = Yii::$app->formatter->asDate($model->created_at, 'php:Y-m-d');
                    $time = Yii::$app->formatter->asDate($model->created_at, 'php:H:i:s');
                    return "<span class=\"nowrap\">$date</span>\n<span class=\"nowrap\">$time</span>";
                }
            ],
        ],
    ]); ?>

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => '/order/default/as-csv']) ?>
        <?php foreach ($searchModel->getSearchState() as $key => $value) : ?>
            <?= Html::hiddenInput('OrderSearch[' . $key . ']', $value) ?>
        <?php endforeach; ?>

        <?= Html::submitButton(Yii::t('app', 'Download CSV'), ['class' => 'btn']) ?>
    <?php ActiveForm::end() ?>

</div>
