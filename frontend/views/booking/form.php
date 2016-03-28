<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\bootstrap\BaseHtml;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\BookingRequestForm
 */
$this->title = 'Заявка на бронирование помещений';
$this->params['breadcrumbs'][] = $this->title; ?>

<h3>Заявка №<?= $model->number ?></h3>

<?php $form = ActiveForm::begin() ?>

<div class="row">

    <div class="col-md-2">
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

    <div class="col-md-2">
        <?= $form->field($model, 'fromTimeString')->widget(TimePicker::className(), $timePickerOptions) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'toTimeString')->widget(TimePicker::className(), $timePickerOptions) ?>
    </div>

</div>

<div class="row">

    <div class="col-md-6"><?= $form->field($model, 'eventPurpose')->textarea() ?></div>

</div>

<?php $vksOptionGroup = 'option-vks';
$equipmentOptionGroup = 'option-equipment';
$options = [
    $model::OPTION_VKS => ['label' => 'С использованием ВКС'],
    $model::OPTION_AUDIO_RECORD => ['label' => 'Сделать аудиозапись', 'group' => $vksOptionGroup],
    $model::OPTION_PROJECTOR => ['label' => 'Выдать проектор', 'group' => $equipmentOptionGroup],
    $model::OPTION_SCREEN => ['label' => 'Выдать экран', 'group' => $equipmentOptionGroup]
]; ?>

<?= $form->field($model, 'options', ['enableClientValidation' => false])
    ->checkboxList(ArrayHelper::getColumn($options, 'label'), ['item' => function ($index, $label, $name, $checked, $value) use ($options) {
        $checkBox = Html::checkbox($name, $checked, [
            'value' => $value,
            'label' => $label,
        ]);
        return Html::tag('div', $checkBox, ['class' => 'checkbox ' . $options[$value]['group']]);
    }]) ?>


<div id="rooms" class="form-group required">

    <?= Html::label($model->getAttributeLabel('rooms')) ?>

    <div class="row">

        <div class="col-md-4">

            <?php $buttons = array_map(function ($item) {
                return [
                    'label' => Html::tag('div', $item['name']) . Html::tag('div', Html::tag('small', $item['description'], ['class' => 'text-muted'])),
                    'options' => [
                        'id' => (string)$item['_id'],
                        'class' => 'btn-default room-group'
                    ]
                ];
            }, $model::roomGroups()) ?>

            <?= \yii\bootstrap\ButtonGroup::widget([
                'options' => [
                    'id' => 'room-group-container',
                    'class' => 'btn-group-vertical'
                ],
                'buttons' => $buttons,
                'encodeLabels' => false
            ]) ?>

        </div>

        <div class="col-md-8">

            <?php $rooms = $model::rooms();
            $items = ArrayHelper::map($rooms,
                function ($item) {
                    return (string)$item->_id;
                },
                function ($item) {
                    return $item->name;
                });

            $options = [
                'item' => function ($index, $label, $name, $checked, $value) use ($rooms) {

                    $checkbox = Html::checkbox($name, $checked, [
                        'value' => $value,
                        'label' => $label,
                        'labelOptions' => [
                            'data' => [
                                'toggle' => 'tooltip',
                                'placement' => 'top',
                                'title' => $rooms[$index]->description,
                                'container' => 'body'
                            ]
                        ]
                    ]);
                    return Html::tag('div', $checkbox, [
                        'class' => 'room checkbox col-md-4',
                        'id' => $value,

                    ]);
                }
            ]; ?>

            <div class="row">

                <?= $form->field($model, 'rooms', ['enableClientValidation' => false])->checkboxList($items, $options)->label(false) ?>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-6"><?= $form->field($model, 'note')->textarea() ?></div>

</div>

<?= Html::submitButton($model->isNewRecord ? '<span class="glyphicon glyphicon-send"></span> Отправить' :
    '<span class="glyphicon glyphicon-save"></span> Сохранить',
    ['class' => 'btn btn-' . ($model->isNewRecord ? 'success' : 'primary')]) ?>

<?php ActiveForm::end() ?>

<?php \frontend\assets\BookingFormAsset::register($this);
$this->registerJs('$("input[value=\'' . $model::OPTION_VKS . '\']").optionActivator(\'div.' . $vksOptionGroup . '\', \'div.' . $equipmentOptionGroup . '\')');
$this->registerJs('$(function () {$(\'[data-toggle="tooltip"]\').tooltip()})') ?>

