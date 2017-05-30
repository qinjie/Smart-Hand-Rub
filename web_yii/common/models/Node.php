<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "node".
 *
 * @property integer $id
 * @property integer $gateway_id
 * @property string $serial
 * @property string $label
 * @property string $remark
 * @property integer $initial_weight
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Gateway $gateway
 * @property NodePress[] $nodePresses
 * @property NodeSummary[] $nodeSummaries
 * @property NodeWeight[] $nodeWeights
 */
class Node extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gateway_id', 'serial'], 'required'],
            [['gateway_id', 'initial_weight', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['serial'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 100],
            [['gateway_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gateway::className(), 'targetAttribute' => ['gateway_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gateway_id' => 'Gateway ID',
            'serial' => 'Serial',
            'label' => 'Label',
            'remark' => 'Remark',
            'initial_weight' => 'Initial Weight',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getNodePresses()
    {
        return $this->hasMany(NodePress::className(), ['node_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodeSummaries()
    {
        return $this->hasMany(NodeSummary::className(), ['node_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodeWeights()
    {
        return $this->hasMany(NodeWeight::className(), ['node_id' => 'id']);
    }
}
