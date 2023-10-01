<?php

use order\models\enums\OrderMode;
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

<li class="<?= $searchModel->getFiltersState()->byMode === null ? 'active' : '' ?>">
    <?=
        Html::a(
            Yii::t('app', 'All'),
            Url::to(["list/$currStatusForUrl", $formName => [
                'searchText' => $filterStateAttrs['searchText'],
                'searchCategory' => $filterStateAttrs['searchCategory'],
                'byService' => $filterStateAttrs['byService'],
            ]])
        )
    ?>
</li>

<?php foreach (OrderMode::values() as $value) : ?>
    <li class="<?= (string)$filterStateAttrs['byMode'] === (string)$value ? 'active' : '' ?>">

        <?php
            $textValue = OrderMode::matchFromInt($value)->getText();
            $textValue = Html::encode(mb_ucfirst($textValue, mb_detect_encoding($textValue)));

            echo Html::a(
                $textValue, 
                Url::to(["list/$currStatusForUrl", $formName => [
                    'searchText' => $filterStateAttrs['searchText'],
                    'searchCategory' => $filterStateAttrs['searchCategory'],
                    'byService' => $filterStateAttrs['byService'],
                    'byMode' => $value
                ]]))
        ?>

    </li>
<?php endforeach; ?>
