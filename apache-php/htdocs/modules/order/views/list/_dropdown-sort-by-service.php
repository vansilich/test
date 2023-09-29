<?php

use order\models\OrderSearch;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var OrderSearch $searchModel
 * @var string $formName
 * @var array $filterStateAttrs
 */
?>

<li class="<?= $filterStateAttrs['byService'] === null ? 'active' : '' ?>">
    <?=
        Html::a(
            sprintf('%s (%d)', Yii::t('app', 'All'), $searchModel->ordersOfServicesCount),
            Url::current(['page' => 1, $formName => [...$filterStateAttrs, 'byService' => null ]])
        )
    ?>
</li>

<?php foreach ($searchModel->servicesByOrdersCount as $service) : ?>

    <li class="<?= (string)$filterStateAttrs['byService'] === (string)$service['id'] ? 'active' : '' ?>">

        <a href="<?= Url::current(['page' => 1, $formName => [...$filterStateAttrs, 'byService' => $service['id'] ]]) ?>">
            <span class="label-id">
                <?= Html::encode($service['orders_cnt']) ?>
            </span>
            <?= Html::encode($service['name']) ?>
        </a>

    </li>
<?php endforeach; ?>
