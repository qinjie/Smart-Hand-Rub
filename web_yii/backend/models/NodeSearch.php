<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Node;
use yii\db\Query;

/**
 * NodeSearch represents the model behind the search form about `app\models\Node`.
 */
class NodeSearch extends Node
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'initial_weight', 'status','last_weight'], 'integer'],
            [['serial', 'label', 'remark', 'created_at', 'updated_at'], 'safe'],
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
        $query = Node::find();
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
            'initial_weight' => $this->initial_weight,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'serial', $this->serial])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByClusterId($params)
    {
        $query = (new Query())->select('node.*,last_weight')->from('node')
            ->join('LEFT JOIN', 'cluster_node','node.id=cluster_node.node_id')
            ->join('LEFT JOIN','node_summary','node.id=node_summary.node_id')
            ->where('cluster_id='.$params['id']);
//            ->andWhere('stats_date=current_date()-1');

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
//            'id' => $this->id,
            'last_weight' => $this->last_weight,
//            'status' => $this->status,
//            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'serial', $this->serial])
            ->andFilterWhere(['like', 'status', $this->label])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

}
