<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClusterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clusters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cluster-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cluster', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model) {
            return ['onclick' => 'location.href="'.Yii::$app->request->BaseUrl.'/cluster/"+('.$model->id.');'];
            },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'label',
            'remark',
//            'created_at',
            'updated_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <div class="alert alert-info alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i>Click on the row to see more Cluster's details.</i>
    </div>
</div>
