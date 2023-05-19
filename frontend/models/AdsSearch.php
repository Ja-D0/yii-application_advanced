<?php

namespace frontend\models;

use common\models\Ads;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;

/**
 * AdsSearch represents the model behind the search form of `common\models\Ads`.
 */
class AdsSearch extends Ads
{
    public $category_name;
    public $search_string;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category'], 'integer'],
            [['search_string'], 'string'],
            [['title', 'author', 'status', 'created_at', 'updated_at', 'description', 'category_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
                'category_name',
                'search_string'
            ]
        );
    }

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
        $query = Ads::find()->where(['author' => \Yii::$app->user->identity->nickname]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                'created_at' => SORT_DESC
            ]
            ]
        ]);

        /*
         * Тут настраивает сортировка поля Категория
         */
        $dataProvider->sort->attributes['category_name'] = [
            'asc' => [Ads::tableName() . '.category' => SORT_ASC],
            'desc' => [Ads::tableName() . '.category' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        //    $query->andFilterWhere([
        //        'id' => $this->id,
        //        'category' => $this->category,
        //    ]);

        $query->andFilterWhere(['like', 'title', $this->search_string]);
        // ->andFilterWhere(['like', 'status', $this->status])
        // ->andFilterWhere(['like', 'created_at', $this->created_at])
        // ->andFilterWhere(['like', 'updated_at', $this->updated_at])
        $query->orFilterWhere(['like', 'description', $this->search_string])->andFilterWhere(['author' => \Yii::$app->user->identity->nickname]);
        // ->andFilterWhere(['like', 'category.title', $this->category_name]);

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = Ads::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                'created_at' => SORT_DESC
            ]
            ]
        ]);

        $dataProvider->sort->attributes['category_name'] = [
            'asc' => [Ads::tableName() . '.category' => SORT_ASC],
            'desc' => [Ads::tableName() . '.category' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'title', $this->search_string]);
        // ->andFilterWhere(['like', 'status', $this->status])
        // ->andFilterWhere(['like', 'created_at', $this->created_at])
        // ->andFilterWhere(['like', 'updated_at', $this->updated_at])
        $query->orFilterWhere(['like', 'description', $this->search_string]);

        return $dataProvider;



    }
}
