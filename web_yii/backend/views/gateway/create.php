<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Gateway */

$this->title = 'Create Gateway';
$this->params['breadcrumbs'][] = ['label' => 'Gateways', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gateway-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
