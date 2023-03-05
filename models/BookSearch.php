<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Books;
use yii\db\Query;

/**
 * BookSearch represents the model behind the search form of `app\models\Books`.
 */
class BookSearch extends Books
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'description', 'publication_date','name_img'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * get a list of all books and authors associated with them
     * @param $limit
     * @param $offset
     * @param $sort_by
     * @return array
     */
    public function getBook($limit,$offset,$sort_by){
        $query = (new Query())
            ->select('*',' DISTINCT')
            ->from('books')
            ->offset($offset)
            ->limit($limit)
            ->orderBy('books.'.$sort_by)
            ->all();
        $result=[];
        foreach ($query as $key=>$item){
            $result[$key]['book']=$item;
            $authorsID = BookAuthor::find()
                ->select('author_id')
                ->where(['book_id'=>$item['id']])
                ->all();
            $qyeryAuthor=[];
            foreach($authorsID as $it=>$el){
                $qyeryAuthor[$it]= Author::find()
                    ->where(['in','id',$el])->all();
            }
            $result[$key]['author']=$qyeryAuthor;
        }
        return $result;
    }

    /**
     * get count of books
     * @return bool|int|string|null
     */
    public function getCountBook(){
        $query = Books::find();
        return $query->count();
    }

    /**
     * Offset of a set of records from a given table,
     * @param $limit
     * @param int $page
     * @return float|int
     */
    public function offsetBook($limit,$page = 1){
        $endNum = ($limit * $page);
        $startNum = ($endNum - $limit);
        return $startNum;
    }

    /**
     * search for books by author's request
     * @param $search
     * @param $limit
     * @param int $offset
     * @param $sort
     * @return array
     */
    public function searchBook($search,$limit,$offset=0,$sort){
        $query = (new Query())
            ->select('books.id')
            ->from('books')
            ->where(['like','title',"%$search%", false])
            ->orWhere(['like','name',"%$search%",false])
            ->orWhere(['like','surname',"%$search%",false])
            ->orWhere(['like','patronymic',"%$search%",false])
            ->innerJoin('book_author','book_author.book_id = books.id')
            ->innerJoin('authors', 'authors.id = book_author.author_id')
            ->offset($offset)
            ->limit($limit)
            ->orderBy('books.'.$sort)//есовместимо с DISTINCT при выдорке одного поля books.id
            ->all();

        $result=[];
        $book = Books::find()
            ->where(['id'=>$query])
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sort)
            ->all();
        foreach ($book as $key=>$item){

            $result[$key]['book']=$item;
            $authorsID = BookAuthor::find()
                ->select('author_id')
                ->where(['book_id'=>$item['id']])
                ->all();

            $qyeryAuthor=[];
            foreach($authorsID as $it=>$el){
                $qyeryAuthor[$it]= Author::find()
                    ->where(['in','id',$el])->all();
            }

            $result[$key]['author']=$qyeryAuthor;
        }
        return $result;
    }

    /**
     * get a book by id
     * @param $id
     * @return mixed
     */
    public function getOneBook($id){

        $result=[];
        $book = Books::find()
            ->where(['id'=>$id])
            ->asArray()
            ->all();
        foreach ($book as $key=>$item){

            $result[$key]['book']=$item;
            $authorsID = BookAuthor::find()
                ->select('author_id')
                ->where(['book_id'=>$item['id']])
                ->all();

            $qyeryAuthor=[];
            foreach($authorsID as $it=>$el){
                $qyeryAuthor[$it]= Author::find()
                    ->where(['in','id',$el])->all();
            }

            $result[$key]['author']=$qyeryAuthor;
        }
        return $result[0];
    }

    public function getAuthor(){
        return $this->hasMany(Author::class,['book_id','id']);
    }


}
