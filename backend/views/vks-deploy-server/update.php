<?php

use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\vks\DeployServer */

$this->title = 'Сервер сборки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сервера сборки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deploy-server-update">

    <?= Html::pageHeader($this->title) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
