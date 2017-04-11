<?php
/**
 * oskr-portal
 * Created: 08.02.17 15:07
 * @copyright Copyright (c) 2017 OSKR NIAEP
 *
 * @var \frontend\models\vks\Request $model
 */
$conference = $model->conference;
?>

<div class="panel panel-default">

    <div class="panel-heading"><strong>Информация по подключению к ВКС</strong></div>

    <?php if ($conference): ?>

        <table class="table">

            <tr>
                <th></th>
                <th>Для видео абонентов</th>
                <th>Для аудио абонентов</th>
            </tr>
            <tr>
                <th>Для внутренних абонентов ОА ИК "АСЭ"</th>
                <td>
                    <div>Номер конференции: <?= $conference->getInternalDS() . $conference->getNumber() ?></div>
                    <div>Пароль: <?= $conference->getPassword() ?>#</div>
                </td>
                <td>
                    <div>Номер телефона: 005 или 200-05</div>
                    <div>Номер конференции: <?= $conference->getInternalDS() . $conference->getNumber() ?></div>
                    <div>Пароль: <?= $conference->getPassword() ?>#</div>
                </td>
            </tr>
            <tr>
                <th>Для сторонних организаций</th>
                <td>
                    <div>Номер
                        конференции: <?= $conference->getExternalDS() . $conference->getNumber() ?></div>
                    <div>Пароль: <?= $conference->getPassword() ?>#</div>
                </td>
                <td>
                    <div>Номер телефона: +7 (831) 422-10-05</div>
                    <div>Номер конференции: <?= $conference->getInternalDS() . $conference->getNumber() ?></div>
                    <div>Пароль: <?= $conference->getPassword() ?>#</div>
                </td>

            </tr>

        </table>

    <?php else: ?>

        <div class="panel-body text-center">Конференция пока не собрана</div>

    <?php endif; ?>

</div>
