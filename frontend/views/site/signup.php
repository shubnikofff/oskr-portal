<?php
use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model \frontend\models\user\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">

    <?= Html::pageHeader($this->title) ?>

    <p>Для регистрации на портале, необходимо указать некоторую информацию о себе. Звездочкой отмечены обязательные к
        заполнению поля.</p>

    <div class="row">

        <div class="col-lg-7">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_LARGE]
            ]); ?>

            <div class="panel panel-default">
                <div class="panel-body">

                    <?= $form->field($model, 'username')->label() ?>

                    <?= $form->field($model, 'email')->widget(MaskedInput::className(), ['clientOptions' => ['alias' => 'email']]) ?>

                    <?= $form->field($model, 'password', ['enableClientValidation' => false])->passwordInput() ?>

                    <?= $form->field($model, 'password_repeat')->passwordInput() ?>

                    <?= $form->field($model, 'lastName') ?>

                    <?= $form->field($model, 'firstName') ?>

                    <?= $form->field($model, 'middleName') ?>

                    <?= $form->field($model, 'companyId')->widget(Select2::className(), [
                        'data' => $model::CompanyItems(),
                        'options' => [
                            'placeholder' => 'Укажите организацию',
                        ],
                    ]) ?>

                    <?= $form->field($model, 'division')->textarea() ?>

                    <?= $form->field($model, 'post') ?>

                    <?= $form->field($model, 'phone') ?>

                    <?= $form->field($model, 'mobile')->widget(MaskedInput::className(), ['mask' => '(999) 999-99-99']) ?>

                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

    <p>
        <small>Учитывайте важность указания корректного адреса электронной почты. Туда будут приходить уведомления,
            такие как изменение статуса заявки и т.д.
        </small>
    </p>

</div>