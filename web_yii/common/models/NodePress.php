<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "node_press".
 *
 * @property integer $id
 * @property integer $node_id
 * @property integer $press
 * @property string $created_at
 *
 * @property Node $node
 */
class NodePress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_press';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'press'], 'required'],
            [['node_id', 'press'], 'integer'],
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
            'press' => 'Press',
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
