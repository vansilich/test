<?php

use app\modules\order\enums\OrderMode;
use app\modules\order\models\OrderSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var OrderSearch $searchModel */
?>

<li class="<?= $searchModel->currFilterByMode === null ? 'active' : '' ?>">
    <a href="">
        <form action="/<?= Yii::$app->controller->action->uniqueId ?>" method="get">
            <?php
                $state = $searchModel->getSearchState();
                $state['currFilterByMode'] = null;
                foreach ($state as $key => $value) :
            ?>
                <?= Html::hiddenInput('OrderSearch[' . $key . ']', $value) ?>
            <?php endforeach; ?>

            <?= Html::submitButton(Yii::t('app', 'All'), ['style' => 'border: none; background: none']) ?>
        </form>
    </a>
</li>

<?php foreach (OrderMode::values() as $value) : ?>
    <li class="<?= (string)$searchModel->currFilterByMode === (string)$value ? 'active' : '' ?>">
        <a href="">
            <form action="/<?= Yii::$app->controller->action->uniqueId ?>" method="get">
                <?php
                    $state = $searchModel->getSearchState();
                    $state['currFilterByMode'] = $value;
                    foreach ($state as $key => $stateVal) :
                ?>
                    <?= Html::hiddenInput('OrderSearch[' . $key . ']', $stateVal) ?>
                <?php endforeach; ?>

                <?php
                    $textValue = OrderMode::matchFromInt($value)->getText();
                    echo Html::submitButton(
                        Html::encode(mb_ucfirst($textValue, mb_detect_encoding($textValue))),
                        ['style' => 'border: none; background: none']
                    )
                ?>
            </form>
        </a>
    </li>
<?php endforeach; ?>
