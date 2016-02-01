<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 11:25
 * @var $this \yii\web\View
 * @var $model \frontend\models\user\UpdateProfileForm
 */
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\widgets\MaskedInput;

$this->title = "Изменение данных";
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => \yii\helpers\Url::to(['user/profile'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <?= Html::pageHeader($this->title) ?>

    <p>Укажите актуальную информацию о себе. Звездочкой отмечены обязательные к заполнению поля.</p>

    <div class="row">

        <div class="col-lg-7">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_LARGE]
            ]); ?>

            <div class="panel panel-default">
                <div class="panel-body">

                    <?= $form->field($model, 'lastName') ?>

                    <?= $form->field($model, 'firstName') ?>

                    <?= $form->field($model, 'middleName') ?>

                    <?= $form->field($model, 'division')->textarea() ?>

                    <?= $form->field($model, 'post') ?>

                    <?= $form->field($model, 'phone') ?>

                    <?= $form->field($model, 'mobile')->widget(MaskedInput::className(), ['mask' => '(999) 999-99-99']) ?>

                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
