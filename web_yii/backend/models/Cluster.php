<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cluster".
 *
 * @property integer $id
 * @property string $label
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClusterNode[] $clusterNodes
 * @property ClusterSummary[] $clusterSummaries
 */
class Cluster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cluster';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'remark'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
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
            'label' => 'Label',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClusterNodes()
    {
        return $this->hasMany(ClusterNode::className(), ['cluster_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClusterSummaries()
    {
        return $this->hasMany(ClusterSummary::className(), ['cluster_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ClusterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClusterQuery(get_called_class());
    }
}
