<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/list.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    public $js = [
        //'js/jquery-1.8.3.min.js',
//        'js/header.js',
        'js/list.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
