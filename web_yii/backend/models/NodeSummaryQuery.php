<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[NodeSummary]].
 *
 * @see NodeSummary
 */
class NodeSummaryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return NodeSummary[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return NodeSummary|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
