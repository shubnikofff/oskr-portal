<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vks-company-form col-md-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'address')->textarea() ?>

    <div class="form-group">

        <?= Html::submitButton('<span class="glyphicon '. ($model->isNewRecord ? 'glyphicon-plus' : 'glyphicon-ok') . '"></span> ' .
            ($model->isNewRecord ? 'Создать' : 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
