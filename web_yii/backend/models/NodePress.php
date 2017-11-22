<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "node_press".
 *
 * @property integer $id
 * @property integer $node_id
 * @property integer $gateway_id
 * @property integer $current_count
 * @property integer $serial_count
 * @property integer $current_weight
 * @property string $created_at
 *
 * @property Gateway $gateway
 * @property Node $node
 */
class NodePress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_press';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'gateway_id', 'current_count', 'serial_count', 'current_weight'], 'required'],
            [['node_id', 'gateway_id', 'current_count', 'serial_count', 'current_weight'], 'integer'],
            [['created_at'], 'safe'],
            [['gateway_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gateway::className(), 'targetAttribute' => ['gateway_id' => 'id']],
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
            'gateway_id' => 'Gateway ID',
            'current_count' => 'Current Count',
            'serial_count' => 'Serial Count',
            'current_weight' => 'Current Weight',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGateway()
    {
        return $this->hasOne(Gateway::className(), ['id' => 'gateway_id']);
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
     * @return NodePressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NodePressQuery(get_called_class());
    }
}
