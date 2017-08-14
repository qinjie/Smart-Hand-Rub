<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "node".
 *
 * @property integer $id
 * @property string $serial
 * @property string $label
 * @property string $remark
 * @property integer $initial_weight
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClusterNode[] $clusterNodes
 * @property NodePress[] $nodePresses
 * @property NodeSummary[] $nodeSummaries
 */
class Node extends \yii\db\ActiveRecord
{
    public $last_weight;
    public $cluster_label;
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
            [['serial', 'label', 'remark', 'initial_weight', 'status'], 'required'],
            [['initial_weight', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['serial'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 100],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial' => 'Serial',
            'label' => 'Name',
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
    public function getClusterNodes()
    {
        return $this->hasMany(ClusterNode::className(), ['node_id' => 'id']);
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
     * @inheritdoc
     * @return NodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NodeQuery(get_called_class());
    }
}
