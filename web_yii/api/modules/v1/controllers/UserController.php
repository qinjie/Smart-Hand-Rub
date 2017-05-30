<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 28/3/15
 * Time: 23:28
 */

namespace api\modules\v1\controllers;

use api\common\controllers\CustomActiveController;

use api\common\models\UserToken;
use common\components\AccessRule;
use common\components\TokenHelper;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use Yii;
use api\common\models\LoginModel;

class UserController extends CustomActiveController
{
    public $modelClass = 'api\common\models\User';
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['login', 'login-email'],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['login', 'login-email'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new UnauthorizedHttpException('You are not authorized');
            },
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'login' => ['post'],
                'logout' => ['get']
            ],
        ];

        return $behaviors;
    }

    public function actionLogin() {
        $request = Yii::$app->request;
        $bodyParams = $request->bodyParams;
        $username = $bodyParams['username'];
        $password = $bodyParams['password'];
        $model = new LoginModel();
        $model->username = $username;
        $model->password = $password;
        if ($user = $model->login()) {
            if ($user->status == User::STATUS_ACTIVE) {
                UserToken::deleteAll(['user_id' => $user->id]);
                $token = TokenHelper::createUserToken($user->id);
                return [
                    'result' => "correct",
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'token' => $token->token,
                    'role' => $user->role
                ];
            } else
                return [
                    "result"=> "wrong"
                ];
        } else {
            return [
                "result"=> "wrong"
            ];
        }

    }

    public function actionLogout(){
        $id = Yii::$app->user->id;
        UserToken::deleteAll(['user_id' => $id]);
        return 'logout successfully';
    }
}