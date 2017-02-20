<?php
/**
 * teleport
 * Created: 16.10.15 10:23
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

use yii\bootstrap\ActiveForm;
use kartik\helpers\Html;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
use frontend\assets\vks\RequestFormAsset;
use yii\helpers\Url;
use yii\helpers\BaseHtml;
use common\components\MinuteFormatter;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\RequestForm
 * @var $submitText string
 */
?>

<div class="vks-request-form row">

    <?php $form = ActiveForm::begin([
        'id' => 'vks-request-form',
        'successCssClass' => '',
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'col-lg-10'
        ]
    ]) ?>

    <?= $form->field($model, 'topic')->textarea() ?>

    <div class="row">

        <?= $form->field($model, 'dateInput', [
            'enableAjaxValidation' => true,
            'options' => [
                'class' => 'col-lg-3 vks-date-time'
            ]
        ])->widget(DatePicker::className(), [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'todayHighlight' => true,
                'startDate' => '0d'
            ],
        ]) ?>

        <?php $timepickerOptions = [
            'pluginOptions' => [
                'showMeridian' => false
            ],
        ] ?>

        <?= $form->field($model, 'beginTimeInput', [
            'enableAjaxValidation' => true,
            'options' => ['class' => 'col-lg-2 vks-date-time']
        ])->widget(TimePicker::className(), $timepickerOptions) ?>

        <?= $form->field($model, 'endTimeInput', [
            'enableAjaxValidation' => true,
            'options' => ['class' => 'col-lg-2 vks-date-time']
        ])->widget(TimePicker::className(), $timepickerOptions) ?>

    </div>

    <p class="help-block"><span class="glyphicon glyphicon-info-sign"></span> Укажите дату не
        ранее <?= Yii::$app->formatter->asDate(time(), 'long') ?> и не
        позднее <?= Yii::$app->formatter->asDate(strtotime("+1 week"), 'long') ?>
        Время должно быть в интервале c <?= MinuteFormatter::asString(Yii::$app->params['vks.minTime']) ?>
        до <?= MinuteFormatter::asString(Yii::$app->params['vks.maxTime']) ?>.
    </p>

    <?= $form->field($model, 'foreignOrganizations', ['inputOptions' => ['id' => 'foreign-organizations']])->inline()->radioList([
        1 => 'С участием',
        0 => 'Без участия'
    ]) ?>

    <div id="rso-files-container" <?= $model->foreignOrganizations == 1 ? '' : 'hidden' ?>>

        <?= $form->field($model, 'rsoUploadedFiles[]', ['enableClientValidation' => false])->fileInput(['multiple' => true])->label("Документы для режимно-секретного отдела") ?>

        <small class="help-block"><span class="glyphicon glyphicon-info-sign"></span> В соответствии с п.2 приказа
            АО &laquo;НИАЭП&raquo; от 19.08.2016 №40/1195-П-дсп &laquo;Об организации проведения видеоконференций,
            аудиоконференций, телемостов с представителями иностранных (международных) организаций&raquo;, Вам
            необходимо прикрепить файл с техническим заданием на ВКС, утвержденным уполномоченным лицом. Если у Вас
            возникли вопросы, необходимо обратиться в режимно-секретный отдел (РСО).
        </small>

    </div>

    <?= $form->field($model, 'mode')->inline()->radioList([
        $model::MODE_WITH_VKS => 'С использованием ВКС',
        $model::MODE_WITHOUT_VKS => 'Без использования ВКС'
    ]) ?>

    <div id="vks-audio-record" style="display: <?= $model->mode === $model::MODE_WITH_VKS ? 'block' : 'none' ?>">
        <p class="help-block"><span class="glyphicon glyphicon-info-sign"></span> Аудиозапись видеоконференций ведется в автоматическом режиме. Срок хранения аудиозаписи - 1 месяц.</p>
    </div>

    <div id="vks-equipment" style="display: <?= $model->mode === $model::MODE_WITHOUT_VKS ? 'block' : 'none' ?>">

        <?= $form->field($model, 'equipment')->checkboxList([
            'проектор' => 'Проектор',
            'экран' => 'Экран'
        ]) ?>

    </div>

    <p class="lead">Абоненты / Переговорные комнаты</p>

    <?php if ($model->hasErrors('participantsId')): ?>

        <?= BaseHtml::error($model, 'participantsId', ['class' => 'alert alert-danger']) ?>

    <?php endif; ?>

    <div id="participants-container">

        <?= $this->render('_participants', ['model' => $model]) ?>

    </div>

    <?= $form->field($model, 'note')->textarea() ?>

    <?= Html::submitButton($submitText, ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php RequestFormAsset::register($this); ?>

<?php
$options = \yii\helpers\Json::encode([
    'refreshParticipantsRoute' => $model->isNewRecord ? Url::to(['refresh-participants']) : Url::to(['refresh-participants', 'requestId' => (string)$model->primaryKey]),
    'participantsContainerSelector' => '#participants-container',
    'dateSelector' => '#requestform-dateinput',
    'beginTimeSelector' => '#requestform-begintimeinput',
    'endTimeSelector' => '#requestform-endtimeinput',
    'dateTimeControlsSelector' => 'div.vks-date-time',
    'modeSelector' => 'input[name="RequestForm[mode]"]',
    'withVksMode' => $model::MODE_WITH_VKS,
    'withoutVksMode' => $model::MODE_WITHOUT_VKS,
    'audioRecordSelector' => '#vks-audio-record',
    'equipmentSelector' => '#vks-equipment'

]);
$this->registerJs("$('form').requestForm({$options});");
?>
