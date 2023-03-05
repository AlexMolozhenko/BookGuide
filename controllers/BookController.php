<?php

namespace app\controllers;

use app\models\Author;
use app\models\BookAuthor;
use app\models\UploadForm;
use Yii;
use app\models\Books;
use app\models\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Books model.
 */
class BookController extends Controller
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
     * Lists all Books models.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * method of searching for books by user request
     */
    public function actionSearchBook(){
        $searchModel = new BookSearch();
        if ($this->request->isPost){
            $offset = $searchModel->offsetBook(self::LIMIT_PAGE,$this->request->post('page'));
            $sort_by= $this->request->post('sort_by');
            $search_query = $this->request->post('query');
            $dataSearch = $searchModel->searchBook($search_query,self::LIMIT_PAGE,$offset,$sort_by);
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
     * method to get all authors
     */
    public function actionGetAuthorList()
    {
        $authors = Author::find()->all();
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->data = $authors;
        $response->send();
    }

    /**
     * method to get all books
     */
    public function actionGetBookList(){
        $searchModel = new BookSearch();
        $count = $searchModel->getCountBook();
        if ($this->request->isPost){
            $offset = $searchModel->offsetBook(self::LIMIT_PAGE,$this->request->post('page'));
            $sort_by= $this->request->post('sort_by');
        }

        $bookdata = $searchModel->getBook(self::LIMIT_PAGE,$offset,$sort_by);
        $data= [
            'count' => $count,
            'limit_page'=>self::LIMIT_PAGE,
            'offset'=>$offset,
            'bookdata'=>$bookdata,
        ];

        $response =  Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->data =  $data ;
        $response->send();
    }

    /**
     * Creates a new Books modele
     */
    public function actionCreate()
    {
        $book = new Books();
        $photo = new UploadForm();
        $response =  Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        $data=null;
        if($this->request->isPost){
            $book->title = $this->request->post('title');
            $book->description = $this->request->post('description');
            $book->publication_date = $this->request->post('publication_date');


            $authors = $this->request->post('authors');
            $act=null;
            if(!$book->validate()){
                $data['massage']='error_save';
                $data['data']=$book->errors;
                $response->data = $data;
                $response->send();
            }else if(empty($authors)){
                $data['massage']='error_save';
                $data['data']['authors']='Author field is required';
                $response->data = $data;
                $response->send();
            }else{
                $photoIndexUNIC = mt_rand(11, 15351131554);
                if(UploadedFile::getInstanceByName('photo')!=null) {
                    $photo->imageFile = UploadedFile::getInstanceByName('photo');
                    if(!$photo->upload($photoIndexUNIC)) {
                        $act=false;
                        $data['massage']='error_save';
                        $data['data']['authors']="file not valid";
                    }else{
                        $book->name_img=$photoIndexUNIC. '.' . $photo->imageFile->extension;
                        $act=true;
                        $data['massage']='successful_save';
                        $data['data']['authors']="file  valid";
                    }
                }else{
                    $book->name_img='';
                    $act=true;
                }
                    if($act==true){
                        $book->save();
                        foreach($authors as $author){
                            $book_author = new BookAuthor();
                            $book_author->author_id = $author;
                            $book_author->book_id = $book->id;
                            $book_author->save(false);
                        }
                        $data=[
                            'massage'=>'successful_save',
                            'data'=>$this->request->post(),

                        ];
                    }
            }
                $response->data = $data;
                $response->send();
            }
    }

    /**
     * method to get book by id
     */
    public function actionGetBook(){
        if($this->request->isPost){
            $id = $this->request->post('id');
            $searchModel = new BookSearch();

            $book = $searchModel->getOneBook($id);


            $response =  Yii::$app->response;
            $response->format = yii\web\Response::FORMAT_JSON;
            $response->data =  $book ;
            $response->send();
        }
    }

    /**
     * Updates an existing Books model.
     */
    public function actionUpdate()
    {
        if($this->request->isPost){
            $updateid = $this->request->post('action');
            $book = Books::find($updateid)
                ->where(['id'=>$updateid])
                ->one();
            $oldPhotoName = $book->name_img;
            $photo = new UploadForm();
            $response =  Yii::$app->response;
            $response->format = yii\web\Response::FORMAT_JSON;
            $data=null;
            if($this->request->isPost){
                $book->title = $this->request->post('title');
                $book->description = $this->request->post('description');
                $book->publication_date = $this->request->post('publication_date');
                $authors = $this->request->post('authors');
                $act=null;
                if(!$book->validate()){
                    $data['massage']='error_save';
                    $data['data']=$book->errors;
                    $response->data = $data;
                    $response->send();
                }else if(empty($authors)){
                    $data['massage']='error_save';
                    $data['data']['authors']='Author field is required';
                    $response->data = $data;
                    $response->send();
                }else{
                    $photoIndexUNIC = mt_rand(11, 15351131554);
                    if(UploadedFile::getInstanceByName('photo')!=null) {
                        $photo->imageFile = UploadedFile::getInstanceByName('photo');
                        if(!$photo->upload($photoIndexUNIC)) {
                            $act=false;
                            $data['massage']='error_save';
                            $data['data']['authors']="file not valid";
                        }else{
                            if($oldPhotoName!=null){
                                if(file_exists('uploads/'.$oldPhotoName)==true){
                                    unlink('uploads/'.$oldPhotoName);
                                }
                            }

                            $book->name_img=$photoIndexUNIC. '.' . $photo->imageFile->extension;
                            $act=true;
                            $data['massage']='successful_save';
                            $data['data']['authors']="file  valid";
                        }
                    }else{
                        if($oldPhotoName!=null){
                            if(file_exists('uploads/'.$oldPhotoName)==true){
                                unlink('uploads/'.$oldPhotoName);
                            }
                        }

                        $book->name_img='';
                        $act=true;
                    }
                    if($act==true){
                        $book->save();
                        $olddataBookAuthor = BookAuthor::find()->where(['book_id'=>$updateid])->all();
                        foreach($olddataBookAuthor as $item){
                            $item->delete();
                        }

                        foreach($authors as $author){
                            $book_author = new BookAuthor();
                            $book_author->author_id = $author;
                            $book_author->book_id = $book->id;
                            $book_author->save(false);
                        }
                        $data=[
                            'massage'=>'successful_save',
                            'data'=>$this->request->post(),

                        ];
                    }
                    $response->data = $data;
                    $response->send();
                }

            }
        }

    }

    /**
     * Deletes an existing Books model.
     */
    public function actionDelete()
    {

        if ($this->request->isPost) {
            $id = $this->request->post('id');
            $book=  Books::find()->where(['id'=>$id])->one();
            $oldPhotoName = $book->name_img;
            $result= $book->delete();
            if($oldPhotoName!=null){
                if(file_exists('uploads/'.$oldPhotoName)==true){
                    unlink('uploads/'.$oldPhotoName);
                }
            }
            $bookAuthor =   BookAuthor::find()->where(['id'=>$id])->all();
            foreach($bookAuthor as $key=> $item){
                $result=$item->delete();
            }

            $response = Yii::$app->response;
            $response->format = yii\web\Response::FORMAT_JSON;
            $response->data = $result;
            $response->send();
        }

    }
}
