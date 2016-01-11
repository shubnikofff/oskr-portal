<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 04.09.15
 * Time: 11:24
 * @var $model \backend\models\PermissionForm
 * @var $this \yii\web\View
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = "Привилегия {$model->name}"
?>

<div class="permission-update">
    <div class="page-header"><h3><?= $this->title ?></h3></div>

    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

    <?= $form->field($model, 'description')->label('Названеие') ?>

    <p>Дочерние привилегии: <?= implode(', ', $model->childrenNames) ?></p>

    <?= $form->field($model, 'ruleName')->dropDownList($model->availableRules) ?>

    <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Сохранить', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>