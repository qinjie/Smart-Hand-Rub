<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cluster_node".
 *
 * @property integer $id
 * @property integer $cluster_id
 * @property integer $node_id
 * @property string $created_at
 *
 * @property Cluster $cluster
 * @property Node $node
 */
class ClusterNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cluster_node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cluster_id', 'node_id'], 'required'],
            [['cluster_id', 'node_id'], 'integer'],
            [['created_at'], 'safe'],
            [['cluster_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cluster::className(), 'targetAttribute' => ['cluster_id' => 'id']],
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
            'cluster_id' => 'Cluster ID',
            'node_id' => 'Node ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCluster()
    {
        return $this->hasOne(Cluster::className(), ['id' => 'cluster_id']);
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
     * @return ClusterNodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClusterNodeQuery(get_called_class());
    }
}
