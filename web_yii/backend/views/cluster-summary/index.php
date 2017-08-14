<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClusterSummarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cluster Summaries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cluster-summary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model) {
            return ['onclick' => 'location.href="'.Yii::$app->request->BaseUrl.'/cluster/"+('.$model['cluster_id'].');'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
            'label','remark',
//            'id',
//            ['attribute' => 'cluster_id',
//                'format' => 'raw',
//                'value' => function ($model) {
//                    return Html::a($model->cluster_id, '/cluster/' . $model->cluster_id);}
//            ],
            'stats_date',
        ['attribute' => 'nodeNumbers',
            'label'=> 'Numbers of Node'],
            'press_count',
            'replenish_count',
             'created_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <div class="alert alert-info alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i>Click on the row to see more Cluster's details.</i>
    </div>
</div>
