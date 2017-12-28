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

    <ul>

        <?php foreach ($list as $item): ?>

            <?php if (($request = $item['request']) instanceof \frontend\models\vks\Request): ?>

                <li><?= Html::a($item['name'] . " " . Yii::$app->formatter->asDate($request->date->toDateTime(), 'long') . " c " .
                        MinuteFormatter::asString($request->beginTime) . " по " . MinuteFormatter::asString($request->endTime),
                        ['vks-request/approve-booking', 'roomId' => (string)$item['_id'], 'requestId' => (string)$request->_id]) ?></li>

            <?php endif; ?>

        <?php endforeach; ?>

    </ul>

</div>
