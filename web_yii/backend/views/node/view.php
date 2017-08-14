<?php

use app\models\NodePressSearch;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\SeriesDataHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Node */
/* @var $searchModel app\models\NodeSummarySearch */
/* @var $searchModel1 app\models\NodePressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProvider1 yii\data\ActiveDataProvider */
/* @var $dataProvider2 yii\data\ActiveDataProvider */


$this->title = 'Node '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-view">

    <h1><?= Html::encode($this->title).'\'s Details' ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this Node?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'serial',
            'label',
            'remark',
            'initial_weight',
//            'status',
            ['attribute' => 'status',
            'format' => 'raw',
                'value' => function($model){
                    return $model->status == '1' ? 'in use' : 'maintenance';
                }],
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <br/>
    <?php
    $searchModel1 = new NodePressSearch();
    $dataProvider1 = $searchModel1->searchByNodeID($model->id);
    $count= array();

    foreach($dataProvider1->models as $data) {
        $count[]=['count' =>(int) $data['current_count'], 'date' => $data['created_at']];
    };
    echo Highcharts::widget([
        'options' => [
            'chart'=> [
                'zoomType'=> 'x'
            ],
            'title' => ['text' => $this->title.' Usage Chart'],
            'yAxis' => [
                'title' => ['text' => 'Counts'],
                'min' => 0,
            ],
            'xAxis'=> [
                'type'=> 'datetime'
            ],
            'plotOptions'=> [
                'area' => [
                    'fillColor'=> [
                        'linearGradient'=> [
                            'x1'=> 0,
                                'y1'=> 0,
                                'x2'=> 0,
                                'y2'=> 1
                            ],
                        'stops'=> [
                            [0, new JsExpression('Highcharts.getOptions().colors[0]'),],
                            [1, '#fff']
                        ]
                        ],
                    'marker'=> [
                        'radius'=> 2
                        ],
                    'lineWidth'=> 1,
                        'states'=> [
                        'hover'=> [
                            'lineWidth' =>2
                            ]
                    ],
                    'threshold'=> null,
                ]
            ],
            'series' => [
                [
                    'type' => 'area',
                    'name' => 'Counts',
                    'data' => new SeriesDataHelper($count, ['date:datetime', 'count']),
                ],
            ]
        ]
    ]);
    ?>

    <?php
    $searchModel2 = new NodePressSearch();
    $dataProvider2 = $searchModel2->searchUsageByNodeID($model->id)->getModels();
    if (count($dataProvider2)>0){
        echo('<p class="bg-info text-center">In <strong>'.$dataProvider2[0]['today'].'</strong>, '.$this->title.' is used the most from <b>'.$dataProvider2[0]['fromHour'].'</b> to <strong>'.$dataProvider2[0]['toHour'].'</strong> with <strong>'.$dataProvider2[0]['count'].'</strong> time(s).</p>');
    } else{
        echo('<p class="bg-warning text-center">Have not yet received any information.</p>');
    }
    ?>

    <h2>Usage Summaries</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],

            'stats_date',
            'press_count',
            ['attribute' => 'last_weight',
                'format' => 'raw',
                'value' => function ($model) {
                    $percent = $model->last_weight/5;
                    if ($percent>30)
                        $html = '<div class="progress-bar progress-bar-striped active"';
                    else  $html = '<div class="progress-bar progress-bar-striped progress-bar-danger active"';

                    $html .= 'role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%">
                                    '.$model->last_weight.'
                             </div>';
                    return $html;
                }
            ],
            ['attribute' => 'to_replenish',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model['to_replenish']=='1' ? 'Yes':'No';}],
            'created_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
