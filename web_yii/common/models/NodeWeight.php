<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "node_weight".
 *
 * @property integer $id
 * @property integer $node_id
 * @property integer $weight
 * @property string $created_at
 *
 * @property Node $node
 */
class NodeWeight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_weight';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'weight'], 'required'],
            [['node_id', 'weight'], 'integer'],
            [['created_at'], 'safe'],
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
            'weight' => 'Weight',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }
}
