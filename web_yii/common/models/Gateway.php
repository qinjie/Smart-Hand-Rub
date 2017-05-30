<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gateway".
 *
 * @property integer $id
 * @property string $serial
 * @property string $label
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GatewaySummary[] $gatewaySummaries
 * @property Node[] $nodes
 */
class Gateway extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gateway';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial', 'label'], 'required'],
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
            'label' => 'Label',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGatewaySummaries()
    {
        return $this->hasMany(GatewaySummary::className(), ['gateway_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['gateway_id' => 'id']);
    }
}
