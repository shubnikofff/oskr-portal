<?php
/**
 * teleport
 * Created: 16.10.15 10:32
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */


use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \common\models\vks\Request
 */
$this->title = "Заявка на Видеоконференцсвязь"
?>

<div class="vks-request-create">

    <?= Html::pageHeader($this->title, 'обновлена ' . Yii::$app->formatter->asDate($model->updatedAt->sec)) ?>

    <p class="lead">Создана <?= Yii::$app->formatter->asDate($model->createdAt->sec) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'submitText' => 'Сохранить'
    ]) ?>

</div>