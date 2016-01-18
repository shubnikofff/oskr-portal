<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\LoginAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

LoginAsset::register($this);
$this->title = 'Вход';
$session = Yii::$app->session;
?>

<div class="site-login">

    <?= $session->hasFlash($model::NO_PERMISSIONS_FLASH) ? "<div class='alert alert-info alert-dismissible'>" . $session->getFlash($model::NO_PERMISSIONS_FLASH) . "</div>" : ''; ?>

    <div class="container">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => false,
            'options' => [
                'class' => 'form-login'
            ]
        ]); ?>

        <h2 class="form-signin-heading">Панель управления</h2>
        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин'])->label(false) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>
        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary btn-block']) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
