<?php
/**
 * oskr-portal
 * Created: 13.11.2017 15:35
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

use yii\bootstrap\ActiveForm;
use kartik\rating\StarRating;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
$this->title = "Оценка качества";

?>
<div>
    <h1>Оценка качества технического обслуживания совещания</h1>
    <br>
    <p><strong>Дата совещания</strong>
        <samp><?= Yii::$app->formatter->asDate($model->date->toDateTime()->getTimestamp(), 'long') ?></samp></p>
    <p><strong>Тема совещания</strong> <samp><?= $model->topic ?></samp></p>

    <br>
    <h4>Пожалуйста оцените качетсво работы нашей службы по Вашей заявке.</h4>
    <p>Оценить нужно по <b>10</b> бальной шкале, где <b>1 - очень плохо</b>, <b>10 - очень хорошо</b>. Если оценка меньше <b>10</b>, то необходимо оставить <b>коментарий</b>.</p>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false
    ]) ?>

    <?= $form->field($model, 'satisfaction')->widget(StarRating::class, [
        'pluginOptions' => [
            'stars' => 10,
            'size' => 'sm',
            'max' => 10,
            'step' => 1,
            'showCaption' => false,
            'showClear' => false,
        ]
    ]) ?>

    <?= $form->field($model, 'feedback')->textarea()->label('Замечания и предложения по улучшению качества технической поддержки') ?>

    <?= \yii\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-warning']) ?>
    <?php ActiveForm::end(); ?>

</div>