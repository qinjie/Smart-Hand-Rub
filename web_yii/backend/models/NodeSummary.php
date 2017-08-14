<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "node_summary".
 *
 * @property integer $id
 * @property integer $node_id
 * @property string $stats_date
 * @property integer $press_count
 * @property integer $last_weight
 * @property integer $to_replenish
 * @property string $created_at
 * @property Node $node
 */
class NodeSummary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'stats_date', 'press_count', 'last_weight', 'to_replenish'], 'required'],
            [['node_id', 'press_count', 'last_weight', 'to_replenish'], 'integer'],
            [['stats_date', 'created_at'], 'safe'],
            [['node_id'], 'exist', 'skipOnError' => true, 'targetClass' => Node::className(), 'targetAttribute' => ['node_id' => 'id']],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'node_id' => 'Node ID',
            'stats_date' => 'Stats Date',
            'press_count' => 'Press Count',
            'last_weight' => 'Last Weight',
            'to_replenish' => 'Need to be Replenished',
            'created_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }


    /**
     * @inheritdoc
     * @return NodeSummaryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NodeSummaryQuery(get_called_class());
    }
}
