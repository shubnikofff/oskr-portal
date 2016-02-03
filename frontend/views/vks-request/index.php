<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 08.10.15
 * Time: 10:52
 */

use kartik\date\DatePicker;
use kartik\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use common\models\vks\Participant;
use yii\helpers\ArrayHelper;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\RequestSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = "График";
?>

    <div class="vks-request-index">

        <?php Modal::begin([
            'id' => 'vks-view-modal-widget',
            'header' => "<h5>Информация по ВКС</h5>"
        ]) ?>

        <div id="vks-view-container"></div>

        <?php Modal::end() ?>

        <?php $form = ActiveForm::begin([
            'id' => 'vks-search-form',
            'action' => ['vks-request/index'],
            'method' => 'get',
            'options' => [
                'class' => 'form-inline'
            ],

            'enableClientValidation' => false,
            'formConfig' => [
                'showLabels' => false
            ]
        ]) ?>

        <?= $form->field($model, 'dateInput')->widget(DatePicker::className(), [
            'type' => DatePicker::TYPE_BUTTON,
            'pluginOptions' => [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'dd.mm.yyyy'
            ],
        ]) ?>

        <?php $query = Participant::find()->select(['_id', 'name', 'companyId'])->with('company');
            $participants = ArrayHelper::toArray($query->all(),[
                Participant::className() => [
                    'id' => function($item) {
                        return (string)$item->primaryKey;
                    },
                    'name',
                    'company' => 'company.name'
                ]
            ]);
            $participantsIdData = ArrayHelper::map($participants, 'id', 'name', 'company'); ?>

        <?= $form->field($model, 'participantsId')->widget(Select2::className(), [
            'data' => $participantsIdData,
            'options' => [
                'placeholder' => 'Фильтр по участникам',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'width' => '600px'
            ]
        ]) ?>

        <?= Html::resetButton('Сброс', ['class' => 'btn btn-primary'])?>

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
        ]) ?>

        <?php Pjax::end() ?>

    </div>

<?php
\frontend\assets\vks\SearchFormAsset::register($this);
$this->registerJs("$('#vks-search-form').searchForm();");
?>