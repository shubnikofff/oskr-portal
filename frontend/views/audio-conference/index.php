<?php
/**
 * oskr-portal
 * Created: 11.05.17 14:26
 * @copyright Copyright (c) 2017 OSKR NIAEP
 *
 * @var \frontend\models\audioconference\AudioConference|null $conference
 */
use yii\helpers\Html;

?>
<div class="container text-center" style="margin-top: 20%">

    <?php if ($conference): ?>

        <p class="lead">Параметры аудиоконференции</p>

        <br>

        <div class="container">

            <div class="col-md-6 col-md-offset-3">

                <table class="table" >

                    <tr>
                        <th>Городской номер</th>
                        <td>+7 (831) 421-81-00</td>

                    </tr>

                    <tr>
                        <th>Внутренний номер (АО ИК «АСЭ»)</th>
                        <td>0-0-4</td>

                    </tr>

                    <tr>
                        <th>Номер аудиоконференции</th>
                        <td><?= $conference->getNumber() ?></td>

                    </tr>

                    <tr>
                        <th>Пароль аудиоконференции</th>
                        <td><?= $conference->getPin() ?>&nbsp;#</td>

                    </tr>
                    <tr>
                        <th>Статус</th>
                        <td><?= $conference->getStatus() ?></td>
                    </tr>


                    <tr>
                        <th>Дата создания</th>
                        <td><?= Yii::$app->formatter->asDatetime($conference->getCreateTime()) ?></td>
                    </tr>

                </table>

            </div>

        </div>

        <br>

        <?= Html::a('Удалить аудиоконференцию', ['audio-conference/delete'], ['class' => 'btn btn-danger', 'data' => ['method' => 'post']]) ?>

    <?php else: ?>

        <p class="lead">Аудиоконференция еще не создана</p>

        <br>

        <?= Html::a('Создать аудиоконференцию', ['audio-conference/create'], ['class' => 'btn btn-success', 'data' => ['method' => 'post']]) ?>


    <?php endif; ?>

</div>
