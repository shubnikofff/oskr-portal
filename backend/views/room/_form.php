<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\models\RoomForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row col-md-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <? /*= $form->field($model, 'groupId')->widget(\kartik\select2\Select2::className(), [
        'data' => ArrayHelper::map(\app\models\RoomForm::groups(),
            function ($item) {
                return (string)$item['_id'];
            }, function ($item) {
                $name = $item['name'];
                if (!empty($item['description'])) {
                    $name .= ' - ' . $item['description'];
                }
                return Html::tag('h1', $name);
            }),
    ]) */ ?>

    <?php $items = ArrayHelper::map(\app\models\RoomForm::groups(),
        function ($item) {
            return (string)$item['_id'];
        }, function ($item) {
            $name = $item['name'];
            if (!empty($item['description'])) {
                $name .= ' - ' . $item['description'];
            }
            return Html::tag('h1', $name);
        }) ?>

    <?= $form->field($model, 'groupId')->dropDownList($items, ['encode' => false, 'id' => 'list']) ?>

    <?= $form->field($model, 'bookingAgreement')->checkbox() ?>

    <?php
    /*$resultsJs = <<< JS
function (data) {
        return {
                results: $.map(data, function (item) {
                    return {
                        text: item.fullName,
                        //slug: '123',
                        id: item.id
                    }
                })
            };
        }
JS;*/
    ?>


    <? /*= $form->field($model, 'agreementPerson')->widget(\kartik\select2\Select2::className(), [
        'options' => [ 'id' => '123',],
        'initValueText' => '12',
        //'options' => ['placeholder' => 'Search for a city ...'],
        'pluginOptions' => [
            'ajax' => [
                'url' => Url::to('/rest/users'),
                'dataType' => 'json',
                'data' => new JsExpression('function(params){return {name: params.term};}'),
                'processResults' => new JsExpression($resultsJs),
            ],
        ]
    ]) */ ?>

    <?= $form->field($model, 'agreementPerson', ['enableClientValidation' => false])->checkboxList([
        '569dea1c6fe10a07008b4567' => 'Шубников АВ',
        '569deaf16fe10a1c008b4567' => 'Серяков ИВ',
        '569df2426fe10a37008b4567' => 'Артамонов ОЮ'
    ]) ?>

    <?= $form->field($model, 'multipleBooking')->checkbox() ?>

    <?= $form->field($model, 'contactPerson')->textarea() ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'ipAddress')->widget(MaskedInput::className(), [
        'clientOptions' => [
            'alias' => 'ip'
        ],
    ]) ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon ' . ($model->isNewRecord ? 'glyphicon-plus' : 'glyphicon-ok') . '"></span> ' .
            ($model->isNewRecord ? 'Создать' : 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php \backend\assets\RoomFormAsset::register($this); ?>

    <?php /*\kartik\select2\Select2Asset::register($this);
    $this->registerJs('$("#list").select2()');*/ ?>

</div>
