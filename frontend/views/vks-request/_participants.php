<?php
/**
 * teleport
 * Created: 27.10.15 9:32
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\MinuteFormatter;
use common\models\RoomGroup;
use yii\helpers\BaseHtml;
use common\models\Room;

/**
 * @var $form \kartik\form\ActiveForm
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\RequestForm
 */
$participants = Room::findAllByRequest($model)
?>

<div id="vks-participants">

    <div id="checked-rooms-container" class="form-group">

        Выбрано:

        <?php if ($model->participantsId): ?>

            <?php foreach ($participants as $participant): ?>

                <?php if (!$participant->isBusy && in_array($participant->primaryKey, $model->participantsId)): ?>

                    <?= Html::beginTag('div', ['class' => 'btn-group checked-room', 'data' => ['room-id' => (string)$participant->getPrimaryKey()]]) ?>

                    <?php $popoverContent = Html::beginTag('dl') .
                        Html::tag('dt', 'Название') . Html::tag('dd', $participant->description) .
                        Html::tag('dt', 'Организация') . Html::tag('dd', $participant->group->name) .
                        Html::tag('dt', 'Технический специалист') . Html::tag('dd', $participant->contactPerson) .
                        Html::tag('dt', 'IP адрес') . Html::tag('dd', $participant->ipAddress) .
                        Html::endTag('dl'); ?>

                    <?= Html::button($participant->name, ['class' => 'btn btn-default btn-room-info', 'data' => [
                        'toggle' => 'popover',
                        'placement' => 'top',
                        'container' => '#vks-participants',
                        'content' => $popoverContent
                    ]]) ?>

                    <?= Html::beginTag('button', ['class' => 'btn btn-default btn-uncheck', 'type' => 'button']) ?>

                    <span class="glyphicon glyphicon-remove text-danger"></span>

                    <?= Html::endTag('button') ?>

                    <?= Html::endTag('div') ?>

                <?php endif; ?>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <small class="help-block"><span class="glyphicon glyphicon-info-sign"></span> Для получения информации о занятости
        переговрных помещений в других ВКС, дата и время совещания должны быть указаны полностью
    </small>

    <div class="form-group">

        <div class="row">

            <div class="col-lg-5">

                <div class="btn-group-vertical btn-block" style="overflow-x: hidden; overflow-y: scroll; max-height: 300px">

                    <?php foreach (RoomGroup::find()->orderBy('order')->all() as $company): ?>

                        <?= Html::button($company->name, [
                            'class' => 'btn btn-default vks-company',
                            'data' => [
                                'id' => (string)$company->primaryKey,
                                'toggle' => 'tooltip',
                                'placement' => 'left',
                                'container' => '#vks-participants',
                                'title' => $company->description
                            ]
                        ]) ?>

                    <?php endforeach; ?>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="row">

                    <?= BaseHtml::activeCheckboxList($model, 'participantsId', ArrayHelper::map($participants, function ($item) {
                        /** @var $item \common\models\Room */
                        return (string)$item->primaryKey;
                    }, 'name'), [
                        'item' => function ($index, $label, $name, $checked, $value) use ($participants) {
                            /** @var Room $participant */
                            $participant = $participants[$index];

                            $defaultOptions = [
                                'value' => $value,
                                'data' => ['company-id' => (string)$participant->groupId]
                            ];

                            $options = array_merge_recursive($defaultOptions, ($participant->isBusy) ? [
                                'label' => $label . '<p><small>занято с ' . MinuteFormatter::asString($participant->busyFrom) . ' до ' .
                                    MinuteFormatter::asString($participant->busyTo) . '</small></p>',
                                'labelOptions' => ['class' => 'disabled'],
                                'disabled' => true
                            ] : [
                                'label' => $label,
                                'data' => [
                                    'name' => $participant->description,
                                    'short-name' => $participant->name,
                                    'company-name' => $participant->group->name,
                                    'contact' => $participant->contactPerson,
                                    'ip-address' => $participant->ipAddress,
                                ],
                            ]);

                            return Html::beginTag('div', ['class' => 'col-lg-4 vks-room', 'style' => 'display:none']) . Html::checkbox($name, $checked, $options) . Html::endTag('div');
                        }
                    ]) ?>

                </div>

            </div>

        </div>

    </div>

    <small class="help-block"><span class="glyphicon glyphicon-info-sign"></span> Если участник отсутствует в списке,
        укажите информацию о нем в примечании:
        название, контакты технического специалиста, ip-адрес
    </small>

</div>

<?php
$options = \yii\helpers\Json::encode([
    'companyButtonsSelector' => 'button.vks-company',
    'vksRoomsSelector' => 'div.vks-room',
    'uncheckButtonsSelector' => 'button.btn-uncheck',
    'infoButtonsSelector' => 'button.btn-room-info',
    'checkedRoomsSelector' => 'div.checked-room',
    'checkedRoomsContainerSelector' => '#checked-rooms-container'
]);
$this->registerJs("$('#vks-participants').participants({$options})");
?>

