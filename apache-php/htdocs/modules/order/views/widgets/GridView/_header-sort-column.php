<?php
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\widgets\ListView;

/** @var string $title */
/** @var callable $itemView */
?>

<div class="dropdown">
    <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?= $title; ?>
        <span class="caret"></span>
    </button>

    <!-- <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'ul',
            'class' => 'dropdown-menu',
            'aria-labelledby' => 'dropdownMenu1'
        ],
        'itemView' => $itemView,
    ]); ?> -->
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">

        <?php foreach ($content as $item): ?>
            <li> <?= $item ?> </li>
        <?php endforeach; ?>

    </ul>
</div>
