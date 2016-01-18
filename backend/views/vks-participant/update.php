<?php
/* @var $this yii\web\View */
/* @var $model \common\models\vks\Participant */

$this->title = $model->name;

?>
<div class="vks-room-update">

    <div class="page-header"><h3><?= $this->title ?></h3></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
