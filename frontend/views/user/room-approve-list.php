<?php
/**
 * oskr.local
 * Created: 19.05.16 17:04
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $list array
 * @var $this \yii\web\View
 */
use kartik\helpers\Html;
use common\components\MinuteFormatter;

$this->title = "Помещения на согласование";

?>
<div>

    <div class="page-header"><?= Html::tag('h3', $this->title) ?></div>

    <ul>

        <?php foreach ($list as $item): ?>

            <li><?= Html::a($item['name'] . " " . Yii::$app->formatter->asDate($item['request']->date->sec) . " c " .
                    MinuteFormatter::asString($item['request']->beginTime) . " по " . MinuteFormatter::asString($item['request']->endTime),
                    ['vks-request/approve-booking', 'roomId' => (string)$item['_id'], 'requestId' => (string)$item['request']->_id]) ?></li>

        <?php endforeach; ?>

    </ul>

</div>
