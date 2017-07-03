<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class IntroAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/goods.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
        'style/jqzoom.css',
    ];
    public $js = [
        'js/header.js',
        'js/goods.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
