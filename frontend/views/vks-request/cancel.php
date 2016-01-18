<?php
/**
 * teleport
 * Created: 16.12.15 14:25
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\widgets\ActiveForm;
use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
$this->title = "Отмена заявки";
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => \yii\helpers\Url::to(['user/requests'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>

    <?= Html::pageHeader($this->title) ?>

    <p>Для отмены заявки, необходимо указать причину:</p>

    <?php $form = ActiveForm::begin([]) ?>

    <?= $form->field($model, 'cancellationReason')->textarea() ?>

    <?= Html::submitButton('Отменить', ['class' => 'btn btn-warning'])?>

    <?php ActiveForm::end() ?>

</div>
