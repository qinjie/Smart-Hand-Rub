<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ClusterNode */

$this->title = 'Create Cluster Node';
$this->params['breadcrumbs'][] = ['label' => 'Cluster Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cluster-node-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
