<?php

use app\models\Books;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php include_once 'bookForm.php';?>

<!--    <p>-->
<!--        --><?//= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->
<!---->
<!--    --><?php //Pjax::begin(); ?>
<!--    --><?php //// echo $this->render('_search', ['model' => $searchModel]); ?>
<!---->
<!--    --><?//= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'title',
//            'description:ntext',
//            'publication_date',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Books $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
//        ],
//    ]); ?>
<!---->
<!--    --><?php //Pjax::end(); ?>

</div>
