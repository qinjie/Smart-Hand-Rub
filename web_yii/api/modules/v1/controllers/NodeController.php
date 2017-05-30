<?php
/**
 * Created by PhpStorm.
 * User: tungphung
 * Date: 3/3/17
 * Time: 9:21 AM
 */

namespace api\modules\v1\controllers;


use api\common\controllers\CustomActiveController;
use common\components\AccessRule;
use common\components\TokenHelper;
use common\models\Device;
use common\models\DeviceToken;
use common\models\GatewayToken;
use common\models\User;
use common\models\UserToken;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;

class NodeController extends CustomActiveController
{
    public $modelClass = 'api\common\models\Node';

    public function behaviors()
    {
        $behaviors = parent::behaviors();


        $behaviors['access'] = [
//            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => [''],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new UnauthorizedHttpException('You are not authorized');
            },
        ];



        return $behaviors;
    }

}