<?php

namespace order\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Support assets fot Internet Explorer 9
 */
class IE9Asset extends AssetBundle
{
    public $jsOptions = ['condition' => 'lte IE9', 'position' => View::POS_HEAD];
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];
    public $js = [
        'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
        'https://oss.maxcdn.com/respond/1.4.2/respond.min.js',
    ];

}
