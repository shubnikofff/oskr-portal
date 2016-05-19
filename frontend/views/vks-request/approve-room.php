<?php
/**
 * oskr.local
 * Created: 18.05.16 14:05
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\ApproveRoomForm
 */
use yii\widgets\ActiveForm;
use kartik\helpers\Html;
use common\components\MinuteFormatter;
use common\models\vks\Participant;

$this->title = "Согласование брони";
$request = $model->request;
$requestIdStr = (string)$request->_id;
?>
<div>

    <div class="page-header"><?= Html::tag('h3', $this->title) ?></div>

    <p>Тема совещания
        <b>&laquo;<?= $model->request->topic ?>&raquo;</b> <?= Html::a('Подробнее >', ['vks-request/view', 'id' => $requestIdStr]) ?>
    </p>

    <p>Дата и время <b><?= Yii::$app->formatter->asDate($request->date->sec) ?></b>
        с <b><?= MinuteFormatter::asString($request->beginTime) ?></b> до
        <b><?= MinuteFormatter::asString($request->endTime) ?></b>
    </p>

    <p>Помещение забронировал <b><?= $request->owner->fullName ?></b>, тел:
        <b><?= $request->owner->phone ?></b> <?= Html::mailto('<span class="glyphicon glyphicon-envelope"></span>', $request->owner->email, ['title' => "Написать письмо"]) ?></p>


    <?php $form = ActiveForm::begin() ?>

    <div class="row">

        <div class="col-lg-6">

            <?= $form->field($model, 'approvedRoomId',  ['errorOptions' => ['class' => 'help-block' ,'encode' => false]])->widget(\kartik\select2\Select2::class, [
                'data' => $model::roomsList(),
                'addon' => [
                    'append' => [
                        'content' => Html::submitButton('Согласовать', ['class' => 'btn btn-success']),
                        'asButton' => true
                    ]
                ]
            ]) ?>

        </div>

    </div>

    <?= Html::a('Отменить бронь', [
        'vks-request/approve-booking', 
        'roomId' => $model->approvedRoomId, 
        'requestId' => $requestIdStr, 
        'status' => Participant::STATUS_CANCEL
    ], ['class' => 'btn btn-danger', 'data-method' => 'POST']) ?>

    <?php ActiveForm::end() ?>

</div>
