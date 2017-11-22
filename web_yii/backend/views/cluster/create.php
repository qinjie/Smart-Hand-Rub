<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cluster */

$this->title = 'Create Cluster';
$this->params['breadcrumbs'][] = ['label' => 'Clusters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cluster-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
