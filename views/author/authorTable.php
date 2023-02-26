
<table class="author_table">
    <tr>
        <th class="num_table">№</th>
        <th class="surname_author">Surname</th>
        <th class="name_author">Name</th>
        <th class="patronymic_author">Patronymic</th>
        <th>Edit/Delete</th>
    </tr>
</table>
<div class="pagination_page">
        <form id="getAuthorListForm">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
        </form>
        <form>

            <input type="hidden" id="input_current_page">
            <input type="hidden" id="input_max_count_page">
        </form>
        <span><button type="button" class="back_page"><</button></span>
        <span class="current_page"></span>
        <span>/</span>
        <span class="max_count_page"></span>
        <span><button type="button" class="forward_page">></button></span>
</div>
<?php //Pjax::begin(); ?>
<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<!--    <pre>-->
<!--        --><?php //var_dump($dataProvider);?>
<!--    </pre>-->
<!--    <pre>-->
<!--        --><?php //var_dump($searchModel); ?>
<!--    </pre>-->


<!--    --><?//= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'name',
//            'surname',
//            'patronymic',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Author $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
//        ],
//    ]); ?>
<!---->
<!--    --><?php //Pjax::end(); ?>
