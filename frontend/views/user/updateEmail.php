<?php
/**
 * teleport
 * Created: 04.12.15 12:51
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\bootstrap\ActiveForm;
use kartik\helpers\Html;
use yii\widgets\MaskedInput;
/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\user\UpdateEmailForm
 */
$this->title = 'Новый Email';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => \yii\helpers\Url::to(['user/profile'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="update=password">

    <?= Html::pageHeader($this->title) ?>

    <p>Для изменения Email, необходимо ввести Ваш пароль</p>

    <div class="row">

        <div class="col-lg-4">

            <?php $form = ActiveForm::begin([]) ?>

            <?= $form->field($model, 'currentPassword')->passwordInput() ?>

            <?= $form->field($model, 'email')->widget(MaskedInput::className(), ['clientOptions' => ['alias' => 'email']]) ?>

            <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end() ?>

        </div>

    </div>

</div>
