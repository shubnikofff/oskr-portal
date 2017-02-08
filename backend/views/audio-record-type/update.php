<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\vks\AudioRecordType */

$this->title = 'Обновить: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Audio Record Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="audio-record-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
