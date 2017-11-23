<?php
/**
 * oskr-portal
 * Created: 20.11.2017 12:39
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

/**
 * @var $model \frontend\models\Report
 * @var $counts array
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $this \yii\web\View
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\i18n\Formatter;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use common\models\User;

$this->title = "Генератор отчетов";
$userListUrl = Url::to(['user-list']);
?>
<div>

    <h1>Генератор отчетов</h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Параметры отчета</h4>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'class' => 'row'
            ]) ?>

            <?= $form->field($model, 'fromDate', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::className(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]) ?>

            <?= $form->field($model, 'toDate', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::className(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]) ?>

            <?= $form->field($model, 'satisfaction', ['options' => ['class' => 'col-md-3']])->dropDownList([
                '' => 'Любая',
                '1' => 'Удовлетворительно',
                '0' => 'Неудовлетворительно'
            ]) ?>


            <?php $employeeDesc = empty($model->employee) ? '' : User::findOne($model->employee)->getFullNameWithPost() ?>
            <?= $form->field($model, 'employee', ['options' => ['class' => 'col-md-3']])->widget(Select2::class, [
                'initValueText' => $employeeDesc, // set the initial display text
                'options' => ['placeholder' => 'Введите фамилию ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Получаю список сотрудников...'; }"),
                    ],
                    'ajax' => [
                        'url' => $userListUrl,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(employee) { return employee.text; }'),
                    'templateSelection' => new JsExpression('function (employee) { return employee.text; }'),
                ]
            ]) ?>

            <div class="col-md-12 text-center" style="margin: 10px 0">
                <?= Html::submitButton('Сформаировать отчет', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>

    <?php if ($dataProvider !== null): ?>

        <?= GridView::widget([

            'dataProvider' => $dataProvider,
            'formatter' => ['class' => Formatter::class, 'nullDisplay' => '-'],
            'columns' => [
                [
                    'attribute' => 'satisfaction',
                    'contentOptions' => ['class' => 'text-center'],
                    'content' => function ($model) {
                        return "<span class='glyphicon " .
                            ($model->satisfaction === '0' ? 'glyphicon-thumbs-down text-danger' : 'glyphicon-thumbs-up text-success') .
                            "'></span>";
                    }
                ],
                'owner.fullNameWithPost',
                [
                    'attribute' => 'date',
                    'content' => function ($model) {
                        return Yii::$app->formatter->asDate($model->date->toDateTime(), 'long');
                    }
                ],
                'feedback',
                [
                    'class' => ActionColumn::class,
                    'controller' => 'vks-request',
                    'template' => '{view}',
                    'contentOptions' => ['class' => 'text-center']
                ]
            ]
        ]) ?>

    <?php endif; ?>

    <?php if ($counts !== null): ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h5>Общие показатели за выбранный период</h5>
            </div>
            <div class="panel-body">
                <ul class="list-inline">
                    <li>Количество конференций: <b><?= $counts['meeting_count'] ?></b></li>
                    <li>Количество участников: <b><?= $counts['participants_count'] ?></b></li>
                    <li>Удовлетворительных оценок: <b><?= $counts['satisfactorily_percent'] ?>%</b></li>
                    <li>Неудовлетворительных оценок: <b><?= $counts['unsatisfactorily_percent'] ?>%</b></li>
                </ul>
            </div>
        </div>

    <?php endif; ?>

</div>
