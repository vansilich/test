<?php

use app\modules\order\models\OrderSearch;
use yii\helpers\Html;

/** @var OrderSearch $searchModel */
?>

<li class="<?= $searchModel->currFilterByService === null ? 'active' : '' ?>">
    <a href="">
        <form action="/<?= Yii::$app->controller->action->uniqueId ?>" method="get">

            <?php
                $state = $searchModel->getSearchState();
                $state['currFilterByService'] = null;
                foreach ($state as $key => $value) :
            ?>
                <?= Html::hiddenInput('OrderSearch[' . $key . ']', $value) ?>
            <?php endforeach; ?>

            <?= Html::submitButton(
            Yii::t('app', 'All') . ' (' . $searchModel->ordersOfServicesCount . ')',
                    ['style' => 'border: none; background: none']
                )
            ?>
        </form>
    </a>
</li>

<?php foreach ($searchModel->servicesByOrdersCount as $service) : ?>

    <li class="<?= $searchModel->currFilterByService === (string)$service['id'] ? 'active' : '' ?>">
        <a href="">
            <form action="/<?= Yii::$app->controller->action->uniqueId ?>" method="get">

                <?php
                    $state = $searchModel->getSearchState();
                    $state['currFilterByService'] = $service['id'];
                    foreach ($state as $key => $value) :
                ?>
                    <?= Html::hiddenInput('OrderSearch[' . $key . ']', $value) ?>
                <?php endforeach; ?>

                <?= Html::submitButton(
            '<span class="label-id">' .
                        Html::encode($service['orders_cnt']) .
                    '</span>' .
                    Html::encode($service['name']),
                    ['style' => 'border: none; background: none']
                ) ?>
            </form>
        </a>
    </li>
<?php endforeach; ?>
