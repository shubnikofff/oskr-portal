<?php
use kartik\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \common\models\RoomGroup */

$this->title = 'Новая компания';
?>
<div class="vks-company-create">

    <?= Html::pageHeader($this->title) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
