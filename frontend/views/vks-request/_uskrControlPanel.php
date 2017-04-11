<?php
/**
 * teleport
 * Created: 22.12.15 16:38
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\bootstrap\ActiveForm;
use common\models\vks\AudioRecordType;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\vks\MCURepository;
use frontend\models\vks\MCUProfileRepository;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */

$mcuServers = ArrayHelper::map(MCURepository::instance()->getRaw(), 'id', 'name');
if($model->conference && $mcuProfiles = MCUProfileRepository::instance()->getRaw($model->conference->getMcuId())) {
    $mcuProfiles = ArrayHelper::map($mcuProfiles, 'id', 'name');
} else {
    $mcuProfiles = [];
}
$audioRecordTypes = AudioRecordType::find()->orderBy('_id')->asArray()->all();
?>

<div class="panel panel-default">

    <div class="panel-heading"><strong>Панель управления УСКР</strong></div>

    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'action' => Url::to([
                $model->status === $model::STATUS_APPROVED ? 'conference/create' : 'vks-request/approve-and-create-conference',
                'requestId' => (string)$model->primaryKey
            ])]) ?>

        <div class="row">

            <?php $conferenceForm = \frontend\models\vks\ConferenceForm::make($model->conference) ?>

            <div class="col-lg-3"><?= $form->field($conferenceForm, 'mcu')->dropDownList($mcuServers, ['id'=>'mcu-id', 'prompt' => 'Не указан']) ?></div>

            <div class="col-lg-3"><?= $form->field($conferenceForm, 'profile')->dropDownList($mcuProfiles, ['id'=>'profile-id', 'prompt' => 'Не указан']); ?></div>

            <div class="col-lg-3"><?= $form->field($conferenceForm, 'audioRecordType')->dropDownList(ArrayHelper::map($audioRecordTypes, '_id', 'name'), ['prompt' => 'Не указан']) ?></div>

            <div class="col-lg-3">

                <div class="row">

                    <div class="col-lg-12" style="padding-top: 22px">

                        <?= Html::submitButton($model->status === $model::STATUS_APPROVED ? "Собрать" : "<span class='glyphicon glyphicon-ok'></span> Согласовать и собрать", ['class' => 'btn btn-success']) ?>

                        <?php if ($model->conference): ?>

                            <?= Html::a("Разобрать", ['conference/destroy', 'requestId' => (string)$model->primaryKey], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите разобрать эту конференцию?',
                                    'method' => 'post',
                                ]]) ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        <?php ActiveForm::end() ?>

    </div>

</div>