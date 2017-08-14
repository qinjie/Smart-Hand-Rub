<?php
/**
 * Created by PhpStorm.
 * User: PNBao
 * Date: 8/10/2017
 * Time: 12:45 PM
 */
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\SubscriptionForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = 'Subscription';
?>
<div class="subscription-subscribe">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Add new Email Subscribers</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'subscription-form']); ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('Subscribe', ['class' => 'btn btn-primary', 'name' => 'subscription-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
