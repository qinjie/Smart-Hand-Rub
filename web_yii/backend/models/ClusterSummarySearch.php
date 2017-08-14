<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClusterSummary;
use yii\db\Query;

/**
 * ClusterSummarySearch represents the model behind the search form about `app\models\ClusterSummary`.
 */
class ClusterSummarySearch extends ClusterSummary
{
    public $label;
    public $remark;
    public $nodeNumbers;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cluster_id', 'press_count', 'replenish_count','nodeNumbers'], 'integer'],
            [['stats_date', 'created_at'], 'safe'],
            [['label','remark'],'string']
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
        $query = (new Query())->select('b.nodeNumbers as nodeNumbers, a.*, cluster.label, cluster.remark')
                                ->from('cluster_summary as a')
                                ->join('LEFT JOIN', 'cluster','a.cluster_id=cluster.id')
                                ->join('LEFT JOIN','(select count(distinct node_id) as nodeNumbers, cluster_id from cluster_node
	                                    group by cluster_id) as b', 'a.cluster_id = b.cluster_id')
                                ->orderBy('a.created_at DESC');
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
            'stats_date' => $this->stats_date,
            'press_count' => $this->press_count,
            'replenish_count' => $this->replenish_count,
            'created_at' => $this->created_at,
            'label' =>$this->label,
            'remark' => $this->remark,
            'nodeNumbers' => $this->nodeNumbers,
        ]);

        return $dataProvider;
    }
}
