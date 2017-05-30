<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 7/4/15
 * Time: 10:26
 */

namespace common\components;

use Yii;
use yii\rbac\Assignment;

class PhpManager extends \yii\rbac\PhpManager
{
    public function init()
    {
        parent::init();
    }

    //-- Retrieve role of a user from database table
    //-- Return an assignment with userId & its role
    public function getAssignments($userId)
    {
        if (!Yii::$app->user->isGuest) {
            $assignment = new Assignment();
            $assignment->userId = $userId;
            # Assume the role is stored in User table "role" column
            $assignment->roleName = Yii::$app->user->identity->role;
            return [$assignment->roleName => $assignment];
        }
        return null;
    }
}