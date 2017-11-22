<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[NodePress]].
 *
 * @see NodePress
 */
class NodePressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return NodePress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return NodePress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
