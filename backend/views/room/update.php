<?php
/* @var $this yii\web\View */
/* @var $model \app\models\RoomForm */
use kartik\helpers\Html;

$this->title = "Комната &laquo;{$model->name}&raquo;"; ?>
<div>

    <?= Html::pageHeader($this->title)?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
