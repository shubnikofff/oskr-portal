<?php

use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\vks\DeployServer */

$this->title = 'Новый сервер сборки';
$this->params['breadcrumbs'][] = ['label' => 'Сервера сборки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deploy-server-create">

    <?= Html::pageHeader($this->title) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
