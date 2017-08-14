<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NodeSummary;
use yii\db\Query;

/**
 * NodeSummarySearch represents the model behind the search form about `app\models\NodeSummary`.
 */

class NodeSummarySearch extends NodeSummary
{
    public $label;
    public $remark;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'node_id', 'press_count', 'last_weight', 'to_replenish'], 'integer'],
            [['stats_date', 'created_at'], 'safe'],
            [['label','remark'],'string'],
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
        $query = (new Query())->select(['{{node_summary}}.*', 'cluster_id','node.label','node.remark'])
            ->from('node_summary')
            ->join('LEFT JOIN','cluster_node', 'node_summary.node_id=cluster_node.node_id')
            ->join('LEFT JOIN','node','node_summary.node_id=node.id')
            ->orderBy('created_at DESC, remark');
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
            'node_id' => $this->node_id,
            'stats_date' => $this->stats_date,
            'press_count' => $this->press_count,
            'last_weight' => $this->last_weight,
            'to_replenish' => $this->to_replenish,
            'created_at' => $this->created_at,
            'label'=>$this->label,
            'remark'=>$this->remark,
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchById($params)
    {
        $query = NodeSummary::find()->select(['{{node_summary}}.*', 'cluster_id'])
            ->from('node_summary')
            ->join('LEFT JOIN','cluster_node', 'node_summary.node_id=cluster_node.node_id')
            ->where('node_summary.node_id='.$params['id']);
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
            'node_id' => $this->node_id,
            'stats_date' => $this->stats_date,
            'press_count' => $this->press_count,
            'last_weight' => $this->last_weight,
            'to_replenish' => $this->to_replenish,
            'created_at' => $this->created_at,

        ]);

        return $dataProvider;
    }
}
