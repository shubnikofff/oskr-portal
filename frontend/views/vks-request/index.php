<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 08.10.15
 * Time: 10:52
 */

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use common\models\vks\Participant;
use yii\helpers\ArrayHelper;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\RequestSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $participantsCountPerHour array
 */

$this->title = "Расписание" ?>

    <div class="vks-request-index">

        <?php Modal::begin([
            'id' => 'vks-view-modal-widget',
            'header' => "<h5>Подробная информация</h5>",
            'size' => Modal::SIZE_LARGE
        ]) ?>

        <div id="vks-view-container"></div>

        <?php Modal::end() ?>

        <?php $form = ActiveForm::begin([
            'id' => 'vks-search-form',
            'action' => ['vks-request/index'],
            'method' => 'get',
            'enableClientValidation' => false,
            'options' => ['class' => 'container schedule-filter-form']
        ]) ?>

        <div class="row">

            <div class="col-lg-10">

                <div class="row">

                    <div class="col-lg-1">

                        <?= $form->field($model, 'dateInput')->widget(DatePicker::className(), [
                            'type' => DatePicker::TYPE_BUTTON,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format' => 'dd.mm.yyyy'
                            ],
                        ]) ?>

                    </div>

                    <div class="col-lg-2">

                        <?= $form->field($model, 'number')->textInput([
                            'size' => 3,
                            'maxlength' => 3
                        ]) ?>


                    </div>

                    <div class="col-lg-4">

                        <?php $query = Participant::find()->select(['_id', 'name', 'companyId'])->with('company');
                        $participants = ArrayHelper::toArray($query->all(), [
                            Participant::class => [
                                'id' => function ($item) {
                                    return (string)$item->primaryKey;
                                },
                                'name',
                                'company' => 'company.name'
                            ]
                        ]);
                        $participantsIdData = ArrayHelper::map($participants, 'id', 'name', 'company'); ?>

                        <?= $form->field($model, 'participantsId')->widget(Select2::class, [
                            'data' => $participantsIdData,
                            'showToggleAll' => false,
                            'options' => [
                                'multiple' => true,
                            ],
                        ]) ?>

                    </div>

                    <div class="col-lg-3">
                        <?= $form->field($model, 'mode')->inline()->radioList([
                            $model::MODE_WITH_VKS => 'с ВКС',
                            $model::MODE_WITHOUT_VKS => 'без ВКС'
                        ]) ?>
                    </div>

                    <div class="col-lg-2" style="padding-top: 25px">

                        <div class="btn-group" role="group" aria-label="...">
                            <button type="submit" class="btn btn-primary"><span
                                        class='glyphicon glyphicon-search'></span></button>
                            <?= Html::a("<span class='glyphicon glyphicon-remove'></span>", ['/vks-request/index'], ['class' => 'btn btn-default']) ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php ActiveForm::end() ?>

        <?php Pjax::begin([
            'formSelector' => '#vks-search-form',
            'options' => [
                'style' => 'padding-top: 20px'
            ],
        ]) ?>

        <?= $this->render('_schedule', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'participantsCountPerHour' => $participantsCountPerHour
        ]) ?>

        <?php Pjax::end() ?>

    </div>

<?php \frontend\assets\vks\SearchFormAsset::register($this);
$this->registerJs("$('#vks-search-form').searchForm();");
$refreshPeriod = Yii::$app->params['vks.schedule.refreshPeriod'];
//TODO не рефрешить прошедшие периоды
$this->registerJs("setInterval(function () {teleport.searchForm.submitForm.call(teleport.searchForm)}, {$refreshPeriod});"); ?>