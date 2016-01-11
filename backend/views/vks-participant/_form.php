<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Company;
use yii\widgets\MaskedInput;
use common\components\helpers\ViewHelper;

/* @var $this yii\web\View */
/* @var $model \common\models\vks\Participant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vks-room-form col-md-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'shortName') ?>

    <?= $form->field($model, 'companyId')->dropDownList(ViewHelper::items(Company::className(), '_id', 'name')) ?>

    <?= $form->field($model, 'ahuConfirmation')->checkbox() ?>

    <?= $form->field($model, 'contact')->textarea() ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'model') ?>

    <?= $form->field($model, 'ipAddress')->widget(MaskedInput::className(), [
        'mask' => '9[9][9].9[9][9].9[9][9].9[9][9]'
    ]) ?>

    <?= $form->field($model, 'gatekeeperNumber') ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon '. ($model->isNewRecord ? 'glyphicon-plus' : 'glyphicon-ok') . '"></span> ' .
            ($model->isNewRecord ? 'Создать' : 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
