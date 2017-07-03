<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FlowAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/cart.css',
        'style/footer.css',
        'style/success.css',
    ];
    public $js = [
//        'js/jquery-1.8.3.min.js',
        'js/cart1.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
