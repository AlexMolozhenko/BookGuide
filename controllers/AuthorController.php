<?php

namespace app\controllers;
use yii;
use app\models\Author;
use app\models\AuthorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends Controller
{
    const LIMIT_PAGE = 15;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Author models.
     *
     * @return string
     */
    public function actionIndex()
    {
//        $searchModel = new AuthorSearch();
//        $dataProvider = $searchModel->search($this->request->queryParams);

//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
        return $this->render('index');
    }

    public function actionGetAuthorList(){


        $searchModel = new AuthorSearch();
        $count = $searchModel->getCountAuthor();
        if ($this->request->isPost){
            $offset = $searchModel->offsetAuthor(self::LIMIT_PAGE,$this->request->post('page'));
        }

//        }else{
//            $offset = $searchModel->offsetAuthor(self::LIMIT_PAGE,Yii::$app->request->post('page'));
//        }

        $dataSearch = $searchModel->getAuthor(self::LIMIT_PAGE,$offset);

        $data = [
            'count' => $count,
            'dataSearch' => $dataSearch,
            'limit_page'=>self::LIMIT_PAGE,
            'offset'=>$offset,
        ];

        $response =  Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->data = $data;
        $response->send();
    }

//    public function actionCountPage(){
//        $authorSearch = new AuthorSearch();
//        $count = $authorSearch->getCountAuthor();
//
//        $response =  Yii::$app->response;
//        $response->format = yii\web\Response::FORMAT_JSON;
//        $response->data = $count;
//        $response->send();
//
//    }

    /**
     * Displays a single Author model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Author model
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Author();
        $response =  Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        if ($this->request->isPost) {
            $model->name =$this->request->post('name');
            $model->surname =$this->request->post('surname');
            $model->patronymic =$this->request->post('patronymic');
            if($model->validate()){
                $response->data = $this->request->post();
                $data=[
                    'massage'=>'successful_save',
                    'data'=>$this->request->post(),
                ];
                $model->save();
            }else{
                $response->data = $data=[
                    'massage'=>'error_save',
                    'data'=> $model->errors,
                ];
            }
        }else{
            $data=[
                'massage'=>'empty data post',
                'data'=>$this->request->post(),
            ];
        }
        $response->data =$data;
        $response->send();
    }

    /**
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Author model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
