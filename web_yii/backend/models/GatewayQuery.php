<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Gateway]].
 *
 * @see Gateway
 */
class GatewayQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Gateway[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Gateway|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
