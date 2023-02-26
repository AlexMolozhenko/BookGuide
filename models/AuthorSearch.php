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
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Author::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'patronymic', $this->patronymic]);

        return $dataProvider;
    }

    public function getAuthor($limit,$offset=0){
        $query = Author::find();

        $author = $query
            ->select('id,surname,name,patronymic')
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id')
            ->all();
        $data=[
            'author'=>$author,
        ];
        return $data;
    }

    public function getCountAuthor(){
        $query = Author::find();
        return $query->count();
    }

    public function offsetAuthor($limit,$page = 1){
        $endNum = ($limit * $page);
        $startNum = ($endNum - $limit);
        return $startNum;
    }

}
