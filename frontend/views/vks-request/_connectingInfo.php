<?php
/**
 * oskr-portal
 * Created: 08.02.17 15:07
 * @copyright Copyright (c) 2017 OSKR NIAEP
 *
 * @var \frontend\models\vks\Request $model
 */
?>

<div class="panel panel-default">

    <div class="panel-heading"><strong>Информация по подключению к ВКС</strong></div>

    <table class="table">
        <tr>
            <td style="width: 40%">Для корпоративных сотрудников ОА ИК АСЭ</td>
            <td style="width: 60%"><?= $model->mcu->prefix . $model->conferenceId ?></td>
        </tr>
        <tr>
            <td>Для участников сторонних организаций</td>
            <td><?= $model->mcu->externalIp . "##" . $model->mcu->prefix . $model->conferenceId ?></td>
        </tr>
    </table>

</div>
