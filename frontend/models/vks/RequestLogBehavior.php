<?php
/**
 * oskr.local
 * Created: 20.06.16 10:38
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\vks;

use common\components\MinuteFormatter;
use common\models\vks\Participant;
use yii\base\Behavior;
use yii\db\ActiveRecord;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RequestLogBehavior
 */
class RequestLogBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'createLog',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'writeLog'
        ];
    }


    public function createLog()
    {
        $log[] = [
            'date' => new \MongoDate(),
            'user' => \Yii::$app->user->identity['fullName'],
            'action' => 'Заявка создана'
        ];
        $this->owner['log'] = $log;
    }

    public function writeLog()
    {
        /** @var RequestForm $model */
        $model = $this->owner;
        $oldAttributes = $model->getOldAttributes();
        $log = $model->log;

        foreach ($model->getDirtyAttributes() as $attribute => $value) {
            $oldValue = $oldAttributes[$attribute];
            if ($oldValue != $value) {
                $row = $this->getLogRow($attribute, $oldValue, $value);
                if ($row) {
                    $log[] = $row;
                }
            }
        }
        $this->owner['log'] = $log;
    }

    /**
     * @param $attribute
     * @param \MongoDate|string|int $oldValue
     * @param \MongoDate|string|int $newValue
     * @return array
     */
    private function getLogRow($attribute, $oldValue, $newValue)
    {
        $row = [
            'date' => new \MongoDate(),
            'user' => \Yii::$app->user->identity['fullName']
        ];

        switch ($attribute) {
            case 'date':
                $row['action'] = "Изменилась дата с " . \Yii::$app->formatter->asDate($oldValue->toDateTime()) . " на " . \Yii::$app->formatter->asDate($newValue->toDateTime());
                return $row;
            case 'beginTime':
                $row['action'] = "Изменилось время начала с " . MinuteFormatter::asString($oldValue) . " на " . MinuteFormatter::asString($newValue);
                return $row;
            case 'endTime':
                $row['action'] = "Изменилось время конца с " . MinuteFormatter::asString($oldValue) . " на " . MinuteFormatter::asString($newValue);
                return $row;
            case 'participantsId':

                $action = '';
                foreach ($newValue as $item) {
                    $index = array_search($item, $oldValue);
                    if ($index !== false) {
                        unset($oldValue[$index]);
                    } else {
                        $action .= "Участник '" . Participant::findOne(['_id' => $item])->shortName . "' был добавлен\n";
                    }
                }

                foreach ($oldValue as $item) {
                    $action .= "Участник '" . Participant::findOne(['_id' => $item])->shortName . "' был удалён\n";
                }

                $row['action'] = $action;
                return $row;
            default:
                return false;
        }
    }
}