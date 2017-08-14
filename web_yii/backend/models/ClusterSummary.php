<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cluster_summary".
 *
 * @property integer $id
 * @property integer $cluster_id
 * @property string $stats_date
 * @property integer $press_count
 * @property integer $replenish_count
 * @property string $created_at
 *
 * @property Cluster $cluster
 */
class ClusterSummary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cluster_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cluster_id', 'stats_date', 'press_count', 'replenish_count'], 'required'],
            [['cluster_id', 'press_count', 'replenish_count'], 'integer'],
            [['stats_date', 'created_at'], 'safe'],
            [['cluster_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cluster::className(), 'targetAttribute' => ['cluster_id' => 'id']],
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
            'stats_date' => 'Stats Date',
            'press_count' => 'Total Presses',
            'replenish_count' => 'Need to be Replenished',
            'created_at' => 'Updated At',
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
     * @inheritdoc
     * @return ClusterSummaryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClusterSummaryQuery(get_called_class());
    }
}
