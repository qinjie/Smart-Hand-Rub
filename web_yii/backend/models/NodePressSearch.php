<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NodePress;
use yii\db\Query;

/**
 * NodePressSearch represents the model behind the search form about `app\models\NodePress`.
 */
class NodePressSearch extends NodePress
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'node_id', 'gateway_id', 'current_count', 'serial_count', 'current_weight'], 'integer'],
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
        $query = NodePress::find();

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
            'gateway_id' => $this->gateway_id,
            'current_count' => $this->current_count,
            'serial_count' => $this->serial_count,
            'current_weight' => $this->current_weight,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function searchByNodeID($node_id)
    {
        $query = (new Query())->select('node_id, current_count, created_at')
                                ->from('node_press')
                                ->where('node_id ='.$node_id)
                                ->orderBy('created_at DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function searchUsageByNodeID($node_id)
    {
        $subquery = (new Query())->select('count(*) AS count')
                                ->from('node_press')
                                ->where('created_at >= t.created_at')
                                ->andWhere('created_at <= adddate(t.created_at, interval 2 hour)');
        $query = (new Query())->select(['date_format(t.created_at, \'%e-%M-%Y\') as today','time_format(t.created_at, \'%H:%i\') as fromHour','time_format(adddate(t.created_at, interval 2 hour), \'%H:%i\') as toHour', 'count'=>$subquery])
            ->from('node_press t')
            ->where('t.node_id='.$node_id)
            ->orderBy('count DESC');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
