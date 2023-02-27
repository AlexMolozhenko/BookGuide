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
        return $this->render('index');
    }

    public function actionGetAuthorList(){


        $searchModel = new AuthorSearch();
        $count = $searchModel->getCountAuthor();
        if ($this->request->isPost){
            $offset = $searchModel->offsetAuthor(self::LIMIT_PAGE,$this->request->post('page'));
            $sort_by= $this->request->post('sort_by');
        }
        $dataSearch = $searchModel->getAuthor(self::LIMIT_PAGE,$offset,$sort_by);

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

    public function actionSearchAuthor(){
        $searchModel = new AuthorSearch();
        if ($this->request->isPost){
            $offset = $searchModel->offsetAuthor(self::LIMIT_PAGE,$this->request->post('page'));
            $sort_by= $this->request->post('sort_by');
            $search_query = $this->request->post('query');
            $dataSearch = $searchModel->searchAuthor($search_query,self::LIMIT_PAGE,$offset,$sort_by);
            $count = count($dataSearch);

        }


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
     */
    public function actionUpdate()
    {
        if ($this->request->isPost) {
            $id = $this->request->post('id');
            $model = Author::findOne(['id'=>$id]);
            $response =  Yii::$app->response;
            $response->format = yii\web\Response::FORMAT_JSON;
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
     * Deletes an existing Author model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        if ($this->request->isPost) {
            $id = $this->request->post('id');
           $result =  Author::findOne(['id'=>$id])->delete();
            $response = Yii::$app->response;
            $response->format = yii\web\Response::FORMAT_JSON;
            $response->data = $result;
            $response->send();
        }

    }


    public function actionGetAuthor()
    {
        if($this->request->isPost){
            $id = $this->request->post('id');
            $author = Author::findOne(['id' => $id]);
                $response = Yii::$app->response;
                $response->format = yii\web\Response::FORMAT_JSON;
                $response->data = $author;
                $response->send();
        }
    }
}
