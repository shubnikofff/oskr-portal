<?php
use kartik\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\user\ResetPasswordForm */

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">

    <?= Html::pageHeader($this->title) ?>

    <p>Пожалуйста введите Ваш новый пароль:</p>

    <div class="row">

        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'password_repeat')->passwordInput() ?>

            <div class="form-group">

                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
