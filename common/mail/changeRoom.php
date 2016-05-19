<?php
/**
 * oskr.local
 * Created: 19.05.16 14:26
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $request \frontend\models\vks\Request
 * @var $oldRoom \common\models\vks\Participant
 * @var $newRoom \common\models\vks\Participant
 */
?>

<p>Здравствуйте!</p>

<p>По Вашей заявке на проведение совещания <b><?= Yii::$app->formatter->asDate($request->date->sec) ?></b> на тему
    <i><?= $request->topic ?></i> была <b>изменена комната</b> с <b><?= $oldRoom->name ?></b> на <b><?= $newRoom->name ?></b>.
</p>

<p>Комната была изменена ответственным за помещения в данном подразделении сотрудником:</p>

<?php $person = $oldRoom->confirmPerson ?>

<p><b><?= $person->fullName ?></b> тел: <b><?= $person->phone ?></b> email: <b><?= $person->email ?></b>.</p>

<p>По всем вопросам, связанным с изменением комнаты, просьба обращаться к выше указанному сотруднику.</p>

<p><b>ОСКР за изменение комнат в заявках ответственности не несет!</b></p>

<p>Спасибо за понимание.</p>