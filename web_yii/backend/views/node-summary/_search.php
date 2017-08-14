<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NodeSummarySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-summary-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'node_id') ?>

    <?= $form->field($model, 'stats_date') ?>

    <?= $form->field($model, 'press_count') ?>

    <?= $form->field($model, 'last_weight') ?>

    <?= $form->field($model, 'to_replenish') ?>

    <?= $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
