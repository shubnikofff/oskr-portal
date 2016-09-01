<?php
/**
 * oskr-portal
 * Created: 28.07.16 10:54
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * @var $request \frontend\models\vks\RequestForm
 * @var $participant \common\models\vks\Participant
 */
?>

<div>

    <p>Здравствуйте!</p>

    <p>Уведомляем Вас o новом бронировании помещения.</p>

    <?= $this->render('booking-detail-view', ['request' => $request, 'participant' => $participant]) ?>

</div>