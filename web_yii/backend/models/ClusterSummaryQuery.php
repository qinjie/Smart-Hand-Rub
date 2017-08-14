<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ClusterSummary]].
 *
 * @see ClusterSummary
 */
class ClusterSummaryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ClusterSummary[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ClusterSummary|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
