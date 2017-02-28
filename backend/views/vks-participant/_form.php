<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Company;
use common\components\helpers\ViewHelper;
use common\models\vks\Participant;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model \common\models\vks\Participant */
/* @var $form yii\widgets\ActiveForm */
$userList = $model::userList();
?>

<div class="vks-room-form col-md-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'shortName') ?>

    <?= $form->field($model, 'companyId')->widget(Select2::class, [
        'data' => ViewHelper::items(Company::class, '_id', 'name')
    ]) ?>

    <?= $form->field($model, 'multiConference')->checkbox() ?>

    <?= $form->field($model, 'ahuConfirmation')->checkbox() ?>

    <?= $form->field($model, 'confirmPersonId')->widget(Select2::class, [
        'data' => $userList,
        'options' => ['placeholder' => 'Выберите пользователя'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'supportEmailsInput') ?>

    <?= $form->field($model, 'observerList')->widget(Select2::class, [
        'data' => $userList,
        'options' => [
            'placeholder' => 'Выберите пользователей',
            'multiple' => true
        ],
    ]) ?>

    <?= $form->field($model, 'dialString') ?>

    <?= $form->field($model, 'protocol')->inline()->radioList([
        Participant::PROTOCOL_H323 => 'H323',
        Participant::PROTOCOL_SIP => 'SIP'
    ]) ?>

    <?= $form->field($model, 'contact')->textarea() ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'model') ?>

    <?= $form->field($model, 'gatekeeperNumber') ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon ' . ($model->isNewRecord ? 'glyphicon-plus' : 'glyphicon-ok') . '"></span> ' .
            ($model->isNewRecord ? 'Создать' : 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
