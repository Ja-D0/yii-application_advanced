<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ads;

/**
 * AdsSearch represents the model behind the search form of `common\models\Ads`.
 */
class AdsSearch extends Ads
{
    public $category_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category'], 'integer'],
            [['title', 'author', 'status', 'created_at', 'updated_at', 'description', 'category_name'], 'safe'],
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
        $query = Ads::find()->joinWith('category_name');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                'created_at' => SORT_DESC
            ]
            ],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'ads.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'ads.title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'category.title', $this->category_name]);

        return $dataProvider;
    }
}
