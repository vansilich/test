<?php

use order\models\OrderSearch;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var OrderSearch $searchModel
 * @var string $formName
 * @var array $filterStateAttrs
 * @var ?string $currStatusForUrl
 */
?>

<li class="<?= $filterStateAttrs['byService'] === null ? 'active' : '' ?>">
    <?=
        Html::a(
            sprintf('%s (%d)', Yii::t('app', 'All'), $searchModel->ordersOfServicesCount),
            Url::to(["list/$currStatusForUrl", $formName => [
                'searchText' => $filterStateAttrs['searchText'],
                'searchCategory' => $filterStateAttrs['searchCategory'],
                'byMode' => $filterStateAttrs['byMode']
            ]])
        )
    ?>
</li>

<?php foreach ($searchModel->servicesByOrdersCount as $service) : ?>

    <li class="<?= (string)$filterStateAttrs['byService'] === (string)$service['id'] ? 'active' : '' ?>">

        <a href="<?= Url::to(["list/$currStatusForUrl", $formName => [
                'searchText' => $filterStateAttrs['searchText'],
                'searchCategory' => $filterStateAttrs['searchCategory'],
                'byService' => $service['id'],
                'byMode' => $filterStateAttrs['byMode']
            ]]) ?>
        ">
            <span class="label-id">
                <?= Html::encode($service['orders_cnt']) ?>
            </span>
            <?= Html::encode($service['name']) ?>
        </a>

    </li>
<?php endforeach; ?>
