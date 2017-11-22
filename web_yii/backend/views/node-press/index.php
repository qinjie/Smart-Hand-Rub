<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodePressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Node Presses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-press-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'node_id',
            'gateway_id',
            'current_count',
            'serial_count',
             'current_weight',
             'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
