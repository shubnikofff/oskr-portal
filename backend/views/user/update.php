<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 16.09.15
 * Time: 13:49
 * @var $this \yii\web\View
 * @var $model \common\models\UserForm
 * @var $user \common\models\User
 */
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

$this->title = "Пользователь $user->username"
?>

<div class="user-update">
    <div class="page-header">
        <h3><?php if ($user->status == $user::STATUS_BLOCKED): ?>
                <span class="glyphicon glyphicon-lock text-danger"></span>
            <?php endif; ?>
            <?= $this->title ?>&nbsp;
            <small>последнее обновление <?= Yii::$app->formatter->asDate($user->updatedAt->sec) ?></small>
        </h3>
    </div>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false
    ]) ?>

    <div class="row">

        <div class="col-lg-7">

            <?= $form->field($model, 'email')->widget(MaskedInput::className(), ['clientOptions' => ['alias' => 'email']]) ?>

            <?= $form->field($model, 'lastName') ?>

            <?= $form->field($model, 'firstName') ?>

            <?= $form->field($model, 'middleName') ?>

            <?= $form->field($model, 'division') ?>

            <?= $form->field($model, 'post') ?>

            <?= $form->field($model, 'phone') ?>

            <?= $form->field($model, 'mobile')->widget(MaskedInput::className(), ['mask' => '(999) 999-99-99']) ?>


            <div class="form-group">
                <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Сохранить', ['class' => 'btn btn-primary']) ?>
                <?php if ($user->status == $user::STATUS_ACTIVE) {
                    echo Html::a('<span class="glyphicon glyphicon glyphicon-lock"></span> Заблокировать', ['change-status', 'id' => (string)$user->getPrimaryKey(), 'status' => $user::STATUS_BLOCKED], ['class' => 'btn btn-danger', 'data-method' => 'post']);
                } elseif ($user->status == $user::STATUS_BLOCKED) {
                    echo Html::a('<span class="glyphicon glyphicon-arrow-up"></span> Активировать', ['change-status', 'id' => (string)$user->getPrimaryKey(), 'status' => $user::STATUS_ACTIVE], ['class' => 'btn btn-success', 'data-method' => 'post']);
                }
                ?>
            </div>

        </div>

        <div class="col-lg-5">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $form->field($model, 'roles')->checkboxList($model->availableRoles) ?>
                </div>
            </div>
        </div>

    </div>

    <?php ActiveForm::end() ?>

</div>