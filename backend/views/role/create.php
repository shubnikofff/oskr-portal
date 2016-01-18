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

$this->title = "Новая роль";
?>

<div class="role-create">

    <div class="page-header"><h3><?= $this->title ?></h3></div>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'name'); ?>

    <?= $form->field($model, 'description') ?>

    <?= $this->render('_children', ['form' => $form, 'model' => $model]) ?>

    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Создать', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end() ?>

</div>
