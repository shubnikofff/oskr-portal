<?php
/**
 * teleport
 * Created: 09.03.16 9:07
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models;

use common\components\Minute;
use common\components\validators\MinuteValidator;
use common\models\Room;
use common\models\RoomGroup;
use yii\mongodb\validators\MongoDateValidator;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BookingRequestForm
 * @property array $busyRooms
 */
class BookingRequestForm extends BookingRequest
{
    const FORMAT_DATE_STRING = 'd LLLL y';
    /**
     * @var string
     */
    public $dateString;
    /**
     * @var string
     */
    public $fromTimeString;
    /**
     * @var string
     */
    public $toTimeString;

    public function rules()
    {
        $minDate = mktime(0, 0, 0);
        $maxDate = strtotime("+1 week");
        $minTime = new Minute(\Yii::$app->params['booking.minTime']);
        $maxTime = new Minute(\Yii::$app->params['booking.maxTime']);
        return array_merge(parent::rules(), [
            [['dateString', 'fromTimeString', 'toTimeString', 'rooms', 'eventPurpose'], 'required'],

            ['dateString', MongoDateValidator::className(), 'format' => self::FORMAT_DATE_STRING,
                'min' => $minDate, 'minString' => \Yii::$app->formatter->asDate($minDate, 'long'),
                'max' => $maxDate, 'maxString' => \Yii::$app->formatter->asDate($maxDate, 'long'),
                'mongoDateAttribute' => 'date'
            ],

            ['fromTimeString', MinuteValidator::className(), 'min' => $minTime, 'max' => $maxTime, 'minuteAttribute' => 'fromTime'],

            ['toTimeString', MinuteValidator::className(), 'min' => $minTime, 'max' => $maxTime, 'minuteAttribute' => 'toTime'],

            ['fromTimeString', function ($attribute) {
                if ($this->fromTime->int >= $this->toTime->int) {
                    $this->addError($attribute, 'время начала должно быть меньше времени окончания');
                }
            }],

        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'dateString' => 'Дата',
            'fromTimeString' => 'Время начала',
            'toTimeString' => 'Время окончания'
        ]);
    }

    public function attributeHints()
    {
        return [
            //'dateString' => 'Максимальная дата'
        ];
    }

    /**
     * @return array|RoomGroup[]
     */
    static public function roomGroups()
    {
        return RoomGroup::find()->orderBy(['order'])->all();
    }

    /**
     * @return array|Room[]
     */
    static public function rooms()
    {
        return Room::find()->orderBy(['name'])->all();
    }

    public function getBusyRooms()
    {

    }

    static public function testLookup()
    {
        /** @var \MongoCollection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Room::collectionName());

        return $collection->aggregate([
            [
                '$lookup' => [
                    'from' => RoomGroup::collectionName(),
                    'localField' => 'groupId',
                    'foreignField' => '_id',
                    'as' => 'group'
                ]
            ]
        ]);
    }
}