<?php
/**
 * oskr-portal
 * Created: 01.09.16 11:13
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * @var $request \frontend\models\vks\Request
 * @var $participant \common\models\vks\Participant
 */
use yii\helpers\Html;

?>
<style type="text/css">
    table {
        border-collapse: collapse;
        text-align: left;
        border: 1px solid #313131;
        padding: 5px;
    }

    td.values {
        font-size: 11pt;
    }

    th {
        color: #3d3d3d;
    }

    td, th {
        padding: 5px;
    }
</style>

<h3>Подробная информация о бронировании</h3>

<?php $organizer = $request->owner ?>

<table>

    <tr>
        <th>Наименование помещения</th>
        <td class="values"><?= $participant->company->name . ' - ' . $participant->name ?></td>
    </tr>
    <tr>
        <th>Дата и время</th>
        <td class="values"><?= Yii::$app->formatter->asDate($request->date->sec, 'long') ?>
            c <?= $request->beginTimeString ?> до <?= $request->endTimeString ?></td>
    </tr>
    <tr>
        <th>Тема совещания</th>
        <td class="values"><?= $request->topic ?></td>
    </tr>
    <tr>
        <th>Совещание в режиме ВКС</th>
        <td class="values"><?= $request->mode === $request::MODE_WITH_VKS ? 'Да' : 'Нет' ?></td>
    </tr>
    <tr>
        <th>Необходимое оборудование</th>
        <td class="values"><?= implode(', ', $request->equipment) ?></td>
    </tr>
    <tr>
        <th>Организатор совещания</th>
        <td class="values"><?= $organizer->fullName ?> - <?= $organizer->post ?></td>
    </tr>
    <tr>
        <th>Контактные данные организатора</th>
        <td class="values">Email: <?= Html::a($organizer->email, 'mailto:' . $organizer->email) ?>,
            Телефон: <?= $organizer->phone ?></td>
    </tr>
    <tr>
        <th>Примечание к заявке на бронирование</th>
        <td class="values"><?= $request->note ?></td>
    </tr>

</table>