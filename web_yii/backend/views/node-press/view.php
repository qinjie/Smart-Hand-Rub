<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\NodePress */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Node Presses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-press-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this Node Press Information?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'node_id',
            'gateway_id',
            'current_count',
            'serial_count',
            'current_weight',
            'created_at',
        ],
    ]) ?>

</div>
