<?php


namespace app\assets;
use yii\web\AssetBundle;

class AuthorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/author.css',
    ];
    public $js = [
        'js/author.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];

}