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
            <th></th>
            <th>Для видео абонентов</th>
            <th>Для аудио абонентов</th>
        </tr>
        <tr>
            <th>Для внутренних абонентов ОА ИК "АСЭ"</th>
            <td>
                <div>Номер конференции: <?= $model->mcu->prefix . $model->conferenceId ?></div>
                <div>Пароль: <?= $model->conferencePassword ?>#</div>
            </td>
            <td>
                <div>Номер телефона: 005 или 200-05</div>
                <div>Номер конференции: <?= $model->mcu->prefix . $model->conferenceId ?></div>
                <div>Пароль: <?= $model->conferencePassword ?>#</div>
            </td>
        </tr>
        <tr>
            <th>Для сторонних организаций</th>
            <td>
                <div>Номер конференции: <?= $model->mcu->externalIp . "##" . $model->mcu->prefix . $model->conferenceId ?></div>
                <div>Пароль: <?= $model->conferencePassword ?>#</div>
            </td>
            <td>
                <div>Номер телефона: +7 (831) 422-10-05</div>
                <div>Номер конференции: <?= $model->mcu->prefix . $model->conferenceId ?></div>
                <div>Пароль: <?= $model->conferencePassword ?>#</div>
            </td>

        </tr>

    </table>

</div>
