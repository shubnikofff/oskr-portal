<?php
use kartik\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \common\models\RoomGroup */

$this->title = $model->name;
?>
<div class="vks-company-update">

    <?= Html::pageHeader($this->title) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
