<?php
use kartik\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \common\models\RoomGroup */

$this->title = "Группа &laquo;{$model->name}&raquo;";
?>
<div>

    <?= Html::pageHeader($this->title) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
