<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\vks\MCU */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, '_id')->textInput(['readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'prefix') ?>

    <?= $form->field($model, 'internalIp') ?>

    <?= $form->field($model, 'externalIp') ?>

    <?= $form->field($model, 'description') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
