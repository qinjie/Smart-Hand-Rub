<?php
/**
 * Created by PhpStorm.
 * User: PNBao
 * Date: 8/10/2017
 * Time: 1:46 PM
 */
namespace common\models;

use Aws\Sns\SnsClient;
use Yii;
use yii\base\Model;

/**
 * Subscription form
 */
class SubscriptionForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
        ];
    }

    public function subscribe($email)
    {
        if ($this->validate()) {
            $client = SnsClient::factory(array(
                'profile' => 'default',
                'region'  => 'ap-southeast-1'
            ));
            print_r($client);
//            $result = $client->subscribe(array(
//                'TopicArn' => 'arn:aws:sns:ap-southeast-1:498107424281:smart_hand_rub_reports',
//                'Protocol' => 'email',
//                'Endpoint' => $email,
//            ));
            return true;
        } else {
            return false;
        }
    }
}
