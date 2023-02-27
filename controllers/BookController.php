<?php

namespace app\controllers;

class BookController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
