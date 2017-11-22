<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodeSummarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nodes Usage Summaries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-summary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="alert alert-danger alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i><strong>Attention!</strong> Node <b>RED</b> means that it needs to be replenished.</i>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model) {
            return ['onclick' => 'location.href="'.Yii::$app->request->BaseUrl.'/node/"+('.$model['node_id'].');'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
//            ['attribute' => 'node_id',
//                'format' => 'raw',
//                'value' => function ($model) {
//                    return Html::a($model['node_id'], '/node/' . $model['node_id']);}
//            ],
            ['attribute' =>'label',
                'label' => 'Name'],
            'remark',
            'stats_date',
            'press_count',
//            'last_weight',
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
//             'to_replenish',
            ['attribute' => 'to_replenish',
                'format' => 'raw',
                'value' => function ($model) {
                            return $model['to_replenish']=='1' ? 'Yes':'No';}],
             'created_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <div class="alert alert-info alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i>Click on the row to see more Node's usage details.</i>
    </div>
</div>
