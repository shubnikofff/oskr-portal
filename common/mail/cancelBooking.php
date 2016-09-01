<?php
/**
 * oskr-portal
 * Created: 28.07.16 10:17
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * @var $request \frontend\models\vks\RequestForm
 * @var $participant \common\models\vks\Participant
 * @var $this \yii\web\View
 */
?>

<div>

    <p>Здравствуйте!</p>

    <p>Уведомляем Вас об отмене бронирования помещения.</p>

    <?= $this->render('booking-detail-view', ['request' => $request, 'participant' => $participant]) ?>

</div>
