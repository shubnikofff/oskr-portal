<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\vks\AudioRecordType */

$this->title = 'Создать новый тип аудизописи';
$this->params['breadcrumbs'][] = ['label' => 'Audio Record Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audio-record-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
