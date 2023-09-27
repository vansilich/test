<?php

use app\modules\order\models\OrderSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var OrderSearch $searchModel */
?>

<li class="<?= $searchModel->currFilterByService === null ? 'active' : '' ?>">
    <a href="">
        <?php $form = ActiveForm::begin(['method' => 'get']) ?>
            <?= Html::hiddenInput('OrderSearch[currFilterByService]', null) ?>
            <?= Html::submitButton(
            Yii::t('app', 'All') . ' (' . $searchModel->servicesCount . ')',
                    ['style' => 'border: none; background: none']
                )
            ?>
        <?php ActiveForm::end() ?>
    </a>
</li>

<?php foreach ($searchModel->servicesByOrdersCount as $service) : ?>

    <li class="<?= $searchModel->currFilterByService === (string)$service['id'] ? 'active' : '' ?>">
        <a href="">
            <?php $form = ActiveForm::begin(['method' => 'get']) ?>
                <?= Html::hiddenInput('OrderSearch[currFilterByService]', $service['id']) ?>
                <?= Html::submitButton(
            '<span class="label-id">' .
                        Html::encode($service['orders_cnt']) .
                    '</span>' .
                    Html::encode($service['name']),
                    ['style' => 'border: none; background: none']
                ) ?>
            <?php ActiveForm::end() ?>
        </a>
    </li>
<?php endforeach; ?>
