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
use common\models\vks\MCU;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */

$mcuServers = MCU::find()->orderBy('_id')->asArray()->all();
$audioRecordTypes = AudioRecordType::find()->orderBy('_id')->asArray()->all();
?>

<div class="panel panel-default">

    <div class="panel-heading"><strong>Панель управления УСКР</strong></div>

    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'action' => Url::to([
                $model->status === $model::STATUS_APPROVE ? 'mcu/deploy' : 'vks-request/approve',
                'requestId' => (string)$model->primaryKey
            ])]) ?>

        <div class="row">

            <div class="col-lg-3"><?= $form->field($model, 'mcuId')->dropDownList(ArrayHelper::map($mcuServers, '_id', 'name')) ?></div>

            <div class="col-lg-3"><?= $form->field($model, 'audioRecordTypeId')->dropDownList(ArrayHelper::map($audioRecordTypes, '_id', 'name')) ?></div>

            <div class="col-lg-6">

                <div class="row">
                    <div class="col-lg-12" style="padding-top: 22px">
                        <?= Html::submitButton($model->status === $model::STATUS_APPROVE ? "<span class='glyphicon glyphicon-wrench'></span> Пересобрать" : "<span class='glyphicon glyphicon-ok'></span> Согласовать и собрать", ['class' => 'btn btn-success']) ?>
                        <?= Html::a("<span class='glyphicon glyphicon-remove'></span> Разобрать", ['mcu/destroy', 'requestId' => (string)$model->primaryKey], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите разобрать эту конференцию?',
                                'method' => 'post',
                            ]]) ?>
                    </div>
                </div>

            </div>

        </div>

        <?php ActiveForm::end() ?>

    </div>

</div>