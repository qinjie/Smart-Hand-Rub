<?php
namespace api\common\controllers;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class CustomActiveController extends ActiveController
{

    public $modelClass = '';

    # Include envelope
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        //-- Include pagination information directly to simplify the client development work
        'collectionEnvelope' => 'items',
    ];

    ## Add authentication
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        # Allow two types of Authentication: Basic & Token
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => ['index', 'view', 'search'],
            'authMethods' => [
                # Add following key-value pair in HTTP header where username:password is 64bit-encoded
                # Authorization     Basic <username:password>
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' => [$this, 'auth'],
                ],
                # Append following behind URL while making request
                # ?access-token=<token>     ?others&access-token=<token>
                QueryParamAuth::className(),
                # Add following key-value pair in HTTP header
                # Authorization     Bearer <token>
                HttpBearerAuth::className(),
            ],
        ];

        return $behaviors;
    }

    # Used by HttpBasicAuth
    public function auth($username, $password)
    {
        // Return Identity object or null
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password))
            return $user;
        else
            return null;
    }

    public function actionSearch()
    {
        if (!empty($_GET)) {
            $model = new $this->modelClass;
            foreach ($_GET as $key => $value) {
                if (!$model->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            try {
                $provider = new ActiveDataProvider([
                    'query' => $model->find()->where($_GET),
                    'pagination' => false
                ]);
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }

            if ($provider->getCount() <= 0) {
                throw new \yii\web\HttpException(404, 'No entries found with this query string');
            } else {
                return $provider;
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }

    }

    public function findModel($id)
    {
//        if (($model = TestPost::findOne($id)) !== null) {
        if (($model = call_user_func($this->modelClass.'::findOne', $id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}