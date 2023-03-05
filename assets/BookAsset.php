<?php
namespace app\assets;
use yii\web\AssetBundle;

class BookAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/book.css',
    ];
    public $js = [
        'js/book.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}