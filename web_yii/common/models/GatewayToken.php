<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gateway_token".
 *
 * @property integer $id
 * @property integer $gateway_id
 * @property string $token
 * @property string $label
 * @property string $mac_address
 * @property string $expire
 * @property string $created_at
 *
 * @property Gateway $gateway
 */
class GatewayToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gateway_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gateway_id'], 'required'],
            [['gateway_id'], 'integer'],
            [['expire', 'created_at'], 'safe'],
            [['token', 'mac_address'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 20],
            [['token'], 'unique'],
            [['gateway_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gateway::className(), 'targetAttribute' => ['gateway_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gateway_id' => 'Gateway ID',
            'token' => 'Token',
            'label' => 'Label',
            'mac_address' => 'Mac Address',
            'expire' => 'Expire',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGateway()
    {
        return $this->hasOne(Gateway::className(), ['id' => 'gateway_id']);
    }
}
