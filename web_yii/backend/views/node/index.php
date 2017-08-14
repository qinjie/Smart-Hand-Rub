<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nodes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Node', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="alert alert-info alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i><strong>Click on the row</strong> to see more Node's usage details.</i>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model) {
            return ['onclick' => 'location.href="'.Yii::$app->request->BaseUrl.'/node/"+('.$model->id.');'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'label',
            'remark',
            'initial_weight',
             ['attribute' => 'status',
                 'format' => 'raw',
                 'value' => function($model){
                    return $model['status'] == '1' ? 'in use' : 'maintenance';
                 }],
//             'status',
//             'created_at',
            'serial',
             'updated_at',
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
