<?php
/* @var $this yii\web\View */
/* @var $model \common\models\vks\Participant */

$this->title = 'Новая переговорная комната';
?>
<div class="vks-room-create">

    <div class="page-header"><h3><?= $this->title ?></h3></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
