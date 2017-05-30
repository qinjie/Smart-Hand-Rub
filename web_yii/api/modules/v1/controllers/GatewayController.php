<?php
/**
 * Created by PhpStorm.
 * User: tungphung
 * Date: 3/3/17
 * Time: 9:21 AM
 */

namespace api\modules\v1\controllers;


use api\common\controllers\CustomActiveController;
use api\common\models\Node;
use common\components\AccessRule;
use common\components\TokenHelper;
use common\models\Gateway;
use common\models\GatewayToken;
use common\models\NodePress;
use common\models\NodeWeight;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;

class GatewayController extends CustomActiveController
{
    public $modelClass = 'api\common\models\Gateway';

    // Remove all default Restful actions
    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        # Use custom authentication through gateway-token
        $behaviors['authenticator']['except'] = [
            'enroll', 'new', 'press', 'weight'
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['enroll', 'new', 'press', 'weight'],
                    'allow' => true,
                    'roles' => ['?'],
                ],

            ],
            'denyCallback' => function ($rule, $action) {
                throw new UnauthorizedHttpException('You are not authorized');
            },
        ];

        return $behaviors;
    }

    public function actionEnroll()
    {
        $headers = Yii::$app->request->headers;
        if (!isset($headers['Serial']))
            throw new HttpException(400, 'Missing <Serial> attribute in header.');
        $token = $headers['Serial'];
        $model = Gateway::findOne(['serial' => $token]);
        if (!$model) {
            throw new UnauthorizedHttpException('You are not authorized');
        }
        GatewayToken::deleteAll(['gateway_id' => $model->id]);
        $token = TokenHelper::createGatewayToken($model->id);

        $array = $model->toArray();
        $array['token'] = $token->token;
        return $array;
    }

    public function actionNew()
    {
        $headers = Yii::$app->request->headers;
        if (!isset($headers['Token']))
            throw new HttpException(400, 'Missing <Token> attribute in header.');
        $token = $headers['Token'];
        $model = GatewayToken::findOne(['token' => $token]);
        if (!$model) {
            throw new UnauthorizedHttpException('You are not authorized');
        }
        $bodyParams = \Yii::$app->request->bodyParams;
        if (!isset($bodyParams['serial']))
            throw new HttpException(400, 'Missing <serial> attribute in bodyParams.');
        if (!isset($bodyParams['initial_weight']))
            throw new HttpException(400, 'Missing <initial_weight> attribute in bodyParams.');
        $serial = $bodyParams['serial'];
        $gateway =  $model->gateway;
        $node = Node::findOne(['serial' => $serial]);
        if ($node) return "Serial existed";
        $node = new Node();
        $node->serial = $serial;
        $node->gateway_id = $gateway->id;
        $node->initial_weight = $bodyParams['initial_weight'];
        if (isset($bodyParams['label'])) $node->label = $bodyParams['label'];
        if (isset($bodyParams['remark'])) $node->remark = $bodyParams['remark'];
        $node->save();
        return $node;
    }
    public function actionPress(){
        $headers = Yii::$app->request->headers;
        if (!isset($headers['Token']))
            throw new HttpException(400, 'Missing <Token> attribute in header.');
        $token = $headers['Token'];
        $model = GatewayToken::findOne(['token' => $token]);
        if (!$model) {
            throw new UnauthorizedHttpException('You are not authorized');
        }

        $bodyParams = \Yii::$app->request->bodyParams;
        if (!isset($bodyParams['node_id']))
            throw new HttpException(400, 'Missing <node_id> attribute in bodyParams.');
        if (!isset($bodyParams['press']))
            throw new HttpException(400, 'Missing <press> attribute in bodyParams.');
        $node_id = $bodyParams['node_id'];
        $node = Node::findOne(['id' => $node_id]);
        if (empty($node)) return "Node not existed";
        $press = $bodyParams['press'];
        $node_press = new NodePress();
        $node_press->node_id = $node_id;
        $node_press->press = $press;
        $node_press->save();
        return $node_press;
    }
    public function actionWeight(){
        $headers = Yii::$app->request->headers;
        if (!isset($headers['Token']))
            throw new HttpException(400, 'Missing <Token> attribute in header.');
        $token = $headers['Token'];
        $model = GatewayToken::findOne(['token' => $token]);
        if (!$model) {
            throw new UnauthorizedHttpException('You are not authorized');
        }

        $bodyParams = \Yii::$app->request->bodyParams;
        if (!isset($bodyParams['node_id']))
            throw new HttpException(400, 'Missing <node_id> attribute in bodyParams.');
        if (!isset($bodyParams['weight']))
            throw new HttpException(400, 'Missing <weight> attribute in bodyParams.');
        $node_id = $bodyParams['node_id'];
        $node = Node::findOne(['id' => $node_id]);
        if (empty($node)) return "Node not existed";
        $weight = $bodyParams['weight'];
        $node_weight = new NodeWeight();
        $node_weight->node_id = $node_id;
        $node_weight->weight = $weight;
        $node_weight->save();
        return $node_weight;
    }
}