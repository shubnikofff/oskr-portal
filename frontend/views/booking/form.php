<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\time\TimePicker;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\BookingRequestForm
 */
$this->title = 'Заявка на бронирование помещений';
$this->params['breadcrumbs'][] = $this->title;

$vksOptionGroup = 'option-vks';
$equipmentOptionGroup = 'option-equipment';
$options = [
    $model::OPTION_VKS => ['label' => 'С использованием ВКС'],
    $model::OPTION_AUDIO_RECORD => ['label' => 'Сделать аудиозапись', 'group' => $vksOptionGroup],
    $model::OPTION_PROJECTOR => ['label' => 'Выдать проектор', 'group' => $equipmentOptionGroup],
    $model::OPTION_SCREEN => ['label' => 'Выдать экран', 'group' => $equipmentOptionGroup]
];

$a = ArrayHelper::getColumn($options, 'label');
?>
<div class="row">

    <div class="col-lg-7">

        <h3>Заявка №<?= $model->number ?></h3>

        <?php $form = ActiveForm::begin() ?>

        <div class="row">

            <div class="col-md-4">
                <?= $form->field($model, 'dateString')->widget(DatePicker::className(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startDate' => '0d',
                        'endDate' => '+7d',
                        'format' => 'd MM yyyy'
                    ]
                ]) ?>
            </div>

            <?php $timePickerOptions = [
                'options' => [
                    'placeholder' => 'ЧЧ:ММ',
                ],
                'pluginOptions' => [
                    'defaultTime' => false,
                    'showSeconds' => false,
                    'showMeridian' => false,
                ],
                'addonOptions' => [
                    'asButton' => true,
                    'buttonOptions' => ['class' => 'btn btn-info']
                ]

            ] ?>

            <div class="col-md-3">
                <?= $form->field($model, 'fromTimeString')->widget(TimePicker::className(), $timePickerOptions) ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'toTimeString')->widget(TimePicker::className(), $timePickerOptions) ?>
            </div>

        </div>

        <?= $form->field($model, 'eventPurpose')->textarea() ?>

        <?= $form->field($model, 'options', [
            'enableClientValidation' => false
        ])->checkboxList(ArrayHelper::getColumn($options, 'label'), ['item' => function ($index, $label, $name, $checked, $value) use ($options) {
            return Html::beginTag('div', ['class' => 'checkbox ' . $options[$value]['group']]) . Html::checkbox($name, $checked, [
                'value' => $value,
                'label' => $label,
            ]) . Html::endTag('div');
        }]) ?>

        <?= $form->field($model, 'rooms') ?>

        <?= $form->field($model, 'note')->textarea() ?>

        <?= Html::submitButton($model->isNewRecord ? '<span class="glyphicon glyphicon-send"></span> Отправить' :
            '<span class="glyphicon glyphicon-save"></span> Сохранить',
            ['class' => 'btn btn-' . ($model->isNewRecord ? 'success' : 'primary')]) ?>

        <?php ActiveForm::end() ?>

    </div>

</div>

<?php \frontend\assets\BookingFormAsset::register($this);
$this->registerJs('$("input[value=\'' . $model::OPTION_VKS . '\']").optionActivator(\'' . $vksOptionGroup . '\', \'' . $equipmentOptionGroup . '\')'); ?>

