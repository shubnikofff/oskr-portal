<?php
/**
 * oskr-portal
 * Created: 13.11.2017 15:35
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

use yii\bootstrap\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
$this->title = "Отзыв";

?>

<div>
    <h1>Отзыв о качестве технического обслуживания совещания</h1>
    <br>
    <p><strong>Дата совещания</strong>
        <samp><?= Yii::$app->formatter->asDate($model->date->toDateTime()->getTimestamp(), 'long') ?></samp></p>
    <p><strong>Тема совещания</strong> <samp><?= $model->topic ?></samp></p>
    <br>
    <h3>Ваша оценка</h3>
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false
    ]) ?>

    <?= $form->field($model, 'satisfaction')->radioList([
        '1' => 'Без замечаний',
        '0' => 'Есть замечания'
    ])->label(false) ?>

    <?= $form->field($model, 'feedback')->textarea()->label('Замечания и предложения по улучшению качества технической поддержки') ?>

    <?= \yii\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

</div>