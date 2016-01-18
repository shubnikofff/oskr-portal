<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 03.09.15
 * Time: 12:42
 * @var $this \yii\web\View
 * @var $model \backend\models\RoleForm
 */
use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = "Роль $model->name";
?>

<div class="role-update">

    <div class="page-header"><h3><?= $this->title ?></h3></div>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'description') ?>

    <?= $this->render('_children', ['form' => $form, 'model' => $model]) ?>

    <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Сохранить', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>
</div>