<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \app\models\RoomForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row col-md-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'groupId')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\RoomForm::groupItems() //TODO добавить описание группы
    ]) ?>

    <?= $form->field($model, 'bookingAgreement')->checkbox() ?>

    <?= $form->field($model, 'multipleBooking')->checkbox() ?>

    <?= $form->field($model, 'contactPerson')->textarea() ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'ipAddress')->widget(MaskedInput::className(), [
        'clientOptions' => [
            'alias' =>  'ip'
        ],
    ]) ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon '. ($model->isNewRecord ? 'glyphicon-plus' : 'glyphicon-ok') . '"></span> ' .
            ($model->isNewRecord ? 'Создать' : 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
