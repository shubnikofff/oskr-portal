<?php
/**
 * teleport
 * Created: 03.12.15 17:09
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\bootstrap\ActiveForm;
use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\user\UpdatePasswordForm
 */
$this->title = 'Новый пароль';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => \yii\helpers\Url::to(['user/profile'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="update=password">

    <?= Html::pageHeader($this->title) ?>

    <p>Для изменения пароля, необходимо указать текуший</p>

    <div class="row">

        <div class="col-lg-4">

            <?php $form = ActiveForm::begin([]) ?>

            <?= $form->field($model, 'currentPassword')->passwordInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'password_repeat')->passwordInput() ?>

            <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end() ?>

        </div>

    </div>

</div>
