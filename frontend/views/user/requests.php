<?php
/**
 * teleport
 * Created: 05.12.15 10:02
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use kartik\helpers\Html;
use frontend\models\vks\Request;
use yii\grid\GridView;
use common\rbac\SystemPermission;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $model \frontend\models\vks\RequestSearch
 */
$this->title = "Заявки";
?>

<div>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'searchKey')->textInput(['placeholder' => 'Введите текст заявки'])->label(false) ?>
        </div>
        <div class="col-md-4" style="vertical-align: bottom">
            <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> Найти', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => \yii\i18n\Formatter::class, 'nullDisplay' => ''],
        'tableOptions' => [
            'class' => 'table'
        ],
        'columns' => [
            [
                'attribute' => 'number',
                'contentOptions' => ['class' => 'text-center']
            ],

            [
                'attribute' => 'date',
                'content' => function (\frontend\models\vks\Request $model) {

                    return Yii::$app->formatter->asDate($model->date->toDateTime(), 'short');
                },
            ],

            [
                'attribute' => 'topic',
                'content' => function ($model) {
                    return Html::a($model->topic, ['vks-request/view', 'id' => (string)$model->primaryKey]);
                },
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Request::statusName($model->status);
                },
            ],

            [
                'class' => \yii\grid\ActionColumn::className(),
                'controller' => 'vks-request',
                'template' => '{delete}',
                'visible' => Yii::$app->user->can(SystemPermission::DELETE_REQUEST)
            ]
        ]
    ]) ?>

</div>
