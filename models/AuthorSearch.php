<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Author;
use yii\data\Pagination;

/**
 * AuthorSearch represents the model behind the search form of `app\models\Author`.
 */
class AuthorSearch extends Author
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'surname', 'patronymic'], 'safe'],
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
     * get all authors
     * @param $limit
     * @param int $offset
     * @param $sort
     * @return array
     */
    public function getAuthor($limit,$offset=0,$sort){
        $query = Author::find();

        $author = $query
            ->select('id,name,patronymic,surname')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sort)
            ->all();
        $data=[
            'author'=>$author,
        ];
        return $data;
    }

    /**
     * get the count of all authors
     * @return bool|int|string|null
     */
    public function getCountAuthor(){
        $query = Author::find();
        return $query->count();
    }

    /**
     * Offset of a set of records from a given table,
     * @param $limit
     * @param int $page
     * @return float|int
     */
    public function offsetAuthor($limit,$page = 1){
        $endNum = ($limit * $page);
        $startNum = ($endNum - $limit);
        return $startNum;
    }

    /**
     * get authors by user request
     * @param $search
     * @param $limit
     * @param int $offset
     * @param $sort
     * @return array
     */
    public function searchAuthor($search,$limit,$offset=0,$sort){
        $query = Author::find();
        $author = $query
            ->select('id,name,patronymic,surname')
            ->where(['like','name',"%$search%", false])
            ->orWhere(['like','surname',"%$search%",false])
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sort)
            ->all();
        $data=[
            'author'=>$author,
        ];
        return $data;
    }
}
