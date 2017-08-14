<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClusterNode;

/**
 * ClusterNodeSearch represents the model behind the search form about `app\models\ClusterNode`.
 */
class ClusterNodeSearch extends ClusterNode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cluster_id', 'node_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = ClusterNode::find();

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
            'cluster_id' => $this->cluster_id,
            'node_id' => $this->node_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
