<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\LoginAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

LoginAsset::register($this);
$this->title = 'Вход';
?>
<div class="site-login">
    <div class="container">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => false,
            'options' => [
                'class' => 'form-login'
            ]
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин'])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>

        <div class="form-control-static">
            <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
