<?php

/* @var $this yii\web\View */

/* @var $searchModel app\models\NodePressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ngee Ann Polytechnic';
?>
<div class="site-index">
    <div class="jumbotron">
        <?php
        if (Yii::$app->user->isGuest) {
        ?>
        <h1>Hello there,</h1>
        <p class="lead">Smart Hand Rub Project.</p>
        <p>Hand hygiene is now regarded as one of he most important element of infection control activities.
            Strict adherence to hand hygiene reduces the risk of cross-transmission of infections.
            It's a must for nurses to hand-rub using sanitizer after he/she contacts a patient.</p>
        <p><a class="btn btn-lg btn-success" href="<?php Yii::$app->request->BaseUrl ?>/site/login">Login</a></p>
        <span>At nursing home, bottles of sanitizers are placed around the rooms and wards. It is difficult to enforce the
            hand hygiene
            practice of individual nurse, or the overall usage in a nursing room/ward.</span>
    </div>
    <?php
    }
    else {
    ?>
    <h1>Hello <?php echo Yii::$app->user->identity->username ?>,</h1>
    <p class="lead">Smart Hand Rub Admin Panel.</p>
    </div>
        <div class="body-content">
            <div class="row">
                <div class="col-lg-4">
                    <h2>Nodes</h2>

                    <p>Each node is a Sanitizer bottle, which is mounted around room. Click below for more details.</p>

                    <p>
                        <a class="btn btn-primary" href="<?php Yii::$app->request->BaseUrl ?>/node">List of Nodes</a>
                        <a class="btn btn-primary" href="<?php Yii::$app->request->BaseUrl ?>/node-summary">Nodes Usage Summary</a>
                    </p>
                </div>
                <div class="col-lg-4">
                    <h2>Clusters</h2>
                    <p>Each cluster is a room/ward in the hospital. Click below for more details.</p>
                    <p>
                        <a class="btn btn-primary" href="<?php Yii::$app->request->BaseUrl ?>/cluster">List of Clusters</a>
                        <a class="btn btn-primary" href="<?php Yii::$app->request->BaseUrl ?>/cluster-summary">Clusters Info Summary</a>
                    </p>
                </div>
                <div class="col-lg-4">
                    <h2>Gateways</h2>
                    <p>Each gateway collect information from Sanitizer bottles and send data to server. Click below for more
                        details.</p>
                    <p><a class="btn btn-primary" href="<?php Yii::$app->request->BaseUrl ?>/gateway">Gateways</a>
                    </p>
                </div>
                <div class="col-lg-4">
                    <h2>Subscribers</h2>
                    <p>Add new email to receive Sanitizers daily reports.</p>
                        <a class="btn btn-primary" href="<?php Yii::$app->request->BaseUrl ?>/subscription">Subscribe</a></p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
