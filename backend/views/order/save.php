<?php
/**
 * teleport.dev
 * Created: 11.02.16 15:17
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */
use kartik\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $model \backend\models\OrderSaver
 */
$this->title = "Порядок отображения";
?>
<div class="order-save">

    <?= Html::pageHeader($this->title) ?>

    <div class="row col-lg-10">

        <?php $items = \yii\helpers\ArrayHelper::map($model->getItems(), function ($item) {
            return (string)$item['_id'];
        }, function($item) {
            return ['content' => $item['name']];
        }) ?>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, $model->orderAttribute)->widget(\kartik\sortinput\SortableInput::className(), [
            'items' => $items
        ])->label(false) ?>

        <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Сохранить', ['class' => 'btn btn-primary'])?>

        <?php ActiveForm::end() ?>

    </div>

</div>
