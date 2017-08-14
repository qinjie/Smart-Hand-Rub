<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClusterNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cluster Nodes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cluster-node-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cluster Node', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            ['attribute' => 'cluster_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->cluster_id, '/cluster/' . $model->cluster_id);}
            ],
            ['attribute' => 'node_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->node_id, '/node/' . $model->node_id);}
            ],
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
