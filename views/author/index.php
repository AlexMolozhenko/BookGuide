<?php
use app\models\Author;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\assets\AuthorAsset;
AuthorAsset::register($this);
/** @var yii\web\View $this */
/** @var app\models\AuthorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <button type="button" class="addAuthor btn btn-primary">+ Author</button>
    <?php include_once 'authorForm.php';?>
    <?php include_once 'authorTable.php';?>
</div>
