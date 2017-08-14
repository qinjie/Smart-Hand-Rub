<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Cluster */
/* @var $searchModel app\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cluster '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Clusters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cluster-view">

    <h1><?= Html::encode($this->title) ?>'s Details</h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this Cluster?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'label',
            'remark',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <br/>
    <h2>All Nodes in <?= Html::encode($this->title) ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model) {
            return ['onclick' => 'location.href="'.Yii::$app->request->BaseUrl.'/node/"+('.$model['id'].');'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'label',
            'remark',
            ['attribute' => 'last_weight',
                'format' => 'raw',
                'value' => function ($model) {
                    $percent = $model['last_weight']/5;
                    if ($percent>30)
                        $html = '<div class="progress-bar progress-bar-striped active"';
                    else  $html = '<div class="progress-bar progress-bar-striped progress-bar-danger active"';

                    $html .= ' role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%">
                                    '.$model['last_weight'].'
                             </div>';
                    return $html;
                }
            ],
            ['attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){
                    return $model['status'] == '1' ? 'in use' : 'maintenance';
                }],
//             'status',
//             'created_at',
            'serial',
            'updated_at',
            ['class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url = Yii::$app->request->BaseUrl.'/node/'.$model['id'];
                        return $url;
                    }
                    elseif ($action === 'update') {
                        $url = Yii::$app->request->BaseUrl.'/update/'.$model['id'];
                        return $url;
                    }
                    elseif ($action === 'delete') {
                        $url = Yii::$app->request->BaseUrl.'/delete/'.$model['id'];
                        return $url;
                    }
                }
                ],
        ],
    ]); ?>
    <div class="alert alert-info alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i>Click on the row to see more Node's usage details.</i>
    </div>
</div>
