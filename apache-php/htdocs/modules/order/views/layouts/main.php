<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\modules\order\assets\DefaultAsset;
use app\modules\order\assets\IE9Asset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\helpers\Url;

DefaultAsset::register($this);
IE9Asset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <title><?= Html::encode($this->title) ?></title>

        <?php $this->head() ?>

        <style>
            .label-default{
                border: 1px solid #ddd;
                background: none;
                color: #333;
                min-width: 30px;
                display: inline-block;
            }
        </style>
    </head>

    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

        <nav class="navbar navbar-fixed-top navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="<?php Url::current() ?>">
                                <?= Html::encode($this->title) ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <?= Alert::widget() ?>

            <?= $content ?>
        </div>

    <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>
