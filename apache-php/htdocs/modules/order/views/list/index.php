<?php

use app\modules\order\models\ActiveRecord\Order;
use order\models\enums\OrderMode;
use order\models\enums\OrderStatus;
use order\models\OrderSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var OrderSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;

$filterStateAttrs = $searchModel->getFiltersState()->attributes;
$formName = $searchModel->getFiltersState()->formName();
?>

<script>
    // cleanup duplicates in query params without reloading page
    let url = '<?= Url::current([$formName => $filterStateAttrs]); ?>';
    window.history.replaceState(null, document.title, url);
</script>

<div class="order-index">

    <ul class="nav nav-tabs p-b">
        <?php foreach ($searchModel->filters[$searchModel::BY_STATUS]->getVariants() as $item): ?>

            <?php $status = OrderStatus::matchFromInt((int)$filterStateAttrs['byStatus']) ?>
            <li class="<?= (string)$item['value'] === $status->getUrlSafeText() ? 'active' : '' ?>">
                <?= Html::a(
                        $item['title'],
                        Url::to(['list/' . $item['value'], 'page' => 1, $formName => $filterStateAttrs])
                    )
                ?>
            </li>
        <?php endforeach; ?>

        <li class="pull-right custom-search">
            <form action="<?= Url::to(['index']) ?>" method="get" class="form-inline">
                <div class="input-group">

                    <?php foreach ($filterStateAttrs as $name => $value): ?>
                        <?= Html::hiddenInput($formName . '[' . $name . ']', $value) ?>
                    <?php endforeach; ?>

                    <?= Html::textInput(
                        $formName . '[searchText]',
                            $filterStateAttrs['searchText'],
                            ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Search orders')]
                        )
                    ?>

                    <span class="input-group-btn search-select-wrap">
                        <?= Html::dropDownList(
                                $formName . '[searchCategory]',
                                $filterStateAttrs['searchCategory'],
                                $searchModel->filters[$searchModel::BY_SEARCH]->getCategories(),
                                ['class' => 'form-control search-select']
                            )
                        ?>

                        <?= Html::submitButton(
                                '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>',
                                ['class' => 'btn btn-default']
                            )
                        ?>
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
                'content' => function (Order $model){
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
                        'searchModel' => $searchModel,
                        'formName' => $formName,
                        'filterStateAttrs' => $filterStateAttrs
                    ]),
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function (Order $model) use ($searchModel) {
                    return '<span class="label-id">' .
                        $searchModel->servicesMapById[$model->service_id]['orders_cnt'] .
                        '</span>' .
                        $model->service->name;
                },
            ],
            [
                'header' => Yii::t('app', 'Status'),
                'content' => function(Order $model){
                    $statusText = Yii::t('app', OrderStatus::matchFromInt($model->status)->getText());
                    return mb_ucfirst($statusText, mb_detect_encoding($statusText));
                }
            ],
            [
                'header' => $this->render('_header-sort-column', [
                    'dataProvider' => $dataProvider,
                    'title' => Yii::t('app', 'Mode'),
                    'itemView' => $this->render('_dropdown-sort-by-mode', [
                        'searchModel' => $searchModel,
                        'formName' => $formName,
                        'filterStateAttrs' => $filterStateAttrs
                    ]),
                ]),
                'headerOptions' => ['class' => 'dropdown-th'],
                'content' => function (Order $model){
                    $modeText = OrderMode::matchFromInt($model->mode)->getText();
                    return mb_ucfirst($modeText, mb_detect_encoding($modeText));
                },
            ],
            [
                'header' => Yii::t('app', 'Created'),
                'content' => function (Order $model){
                    $date = Yii::$app->formatter->asDate($model->created_at, 'php:Y-m-d');
                    $time = Yii::$app->formatter->asDate($model->created_at, 'php:H:i:s');
                    return "<span class=\"nowrap\">$date</span>\n<span class=\"nowrap\">$time</span>";
                }
            ],
        ],
    ]); ?>

    <div class="col-sm-12" style="display: flex; align-items: end; flex-direction: column;">
        <?php $form = ActiveForm::begin(['method' => 'post', 'action' => Url::to(['as-csv'], true)]) ?>

            <?php foreach ($filterStateAttrs as $key => $_): ?>
                <?= $form->field($searchModel->getFiltersState(), $key,
                        ['template' => '{input}', 'options' => ['tag' => false]]
                    )->hiddenInput()
                ?>
            <?php endforeach; ?>

            <?= Html::submitButton(Yii::t('app', 'Download CSV'), ['class' => 'btn']) ?>

        <?php ActiveForm::end() ?>
    </div>

</div>
