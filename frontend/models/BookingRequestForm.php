<?php
/**
 * teleport
 * Created: 09.03.16 9:07
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models;

use common\models\Room;
use common\models\RoomGroup;
use yii\mongodb\Connection;
use yii\mongodb\validators\MongoIdValidator;
use yii\validators\DateValidator;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BookingRequestForm
 *
 * @property array $roomGroups
 */
class BookingRequestForm extends BookingRequest
{
    const SCENARIO_BUSY_ROOMS = 'busy_rooms';
    const FORMAT_DATE_TIME = 'd.m.Y H:i';
    const FORMAT_TIME = 'H:i';

    /**
     * @var string
     */
    public $duration;
    /**
     * @var \MongoId
     */
    public $requestId;
    /**
     * @var int
     */
    protected $maxTime;
    /**
     * @var int
     */
    protected $minTime;
    /**
     * @var int
     */
    protected $minHour;
    /**
     * @var int
     */
    protected $maxHour;
    /**
     * @var \DateTime
     */
    private $_fromDateTime;
    /**
     * @var \DateTime
     */
    private $_toDateTime;
    /**
     * @var array
     */
    private $_roomGroups;

    public function init()
    {
        parent::init();

        $this->minHour = \Yii::$app->params['booking.minHour'];
        $this->maxHour = \Yii::$app->params['booking.maxHour'];
        $this->minTime = mktime($this->minHour, 0, 0);
        $this->maxTime = mktime($this->maxHour, 0, 0, date('n'), date('j') + 7);
    }

    public function afterFind()
    {
        parent::afterFind();

        if ($this->toTime instanceof \MongoDate) {
            $diff = $this->fromTime->toDateTime()->diff($this->toTime->toDateTime());
            $this->duration = $diff->format('%H:%I');
        }

        if ($this->fromTime instanceof \MongoDate) {
            $this->fromTime = \Yii::$app->formatter->asDatetime($this->fromTime->toDateTime(), 'php:' . self::FORMAT_DATE_TIME);
        }
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_BUSY_ROOMS => ['requestId', 'fromTime', 'duration']
        ]);
    }


    public function rules()
    {
        return array_merge(parent::rules(), [

            [['fromTime', 'duration', 'eventPurpose', 'rooms'], 'required'],

            ['fromTime', 'checkFromTime'],
            ['duration', 'checkDuration'],

            ['options', 'in', 'range' => [self::OPTION_VKS, self::OPTION_AUDIO_RECORD, self::OPTION_PROJECTOR, self::OPTION_SCREEN], 'allowArray' => true],

            ['rooms', 'exist', 'targetClass' => Room::className(), 'targetAttribute' => '_id', 'allowArray' => true],

            ['note', 'safe'],

            ['requestId', MongoIdValidator::className(), 'forceFormat' => 'object']
        ]);
    }

    public function checkFromTime($attribute)
    {
        (new DateValidator([
            'format' => 'php:' . self::FORMAT_DATE_TIME,
            'min' => $this->minTime,
            'max' => $this->maxTime,
            'minString' => \Yii::$app->formatter->asDatetime($this->minTime),
            'maxString' => \Yii::$app->formatter->asDatetime($this->maxTime)
        ]))->validateAttribute($this, $attribute);

        if (!$this->hasErrors($attribute)) {
            $this->_fromDateTime = date_create_from_format(self::FORMAT_DATE_TIME, $this->{$attribute});
            $hour = (int)date('G', $this->_fromDateTime->getTimestamp());
            if (!($hour >= $this->minHour && $hour < $this->maxHour)) {
                $this->addError($attribute, "Время должно быть в интервале от {$this->minHour}:00 до {$this->maxHour}:00");
            }
        }
    }

    public function checkDuration($attribute)
    {
        (new DateValidator(['format' => 'php:' . self::FORMAT_TIME]))->validateAttribute($this, $attribute);

        if (!$this->hasErrors($attribute) && $this->_fromDateTime instanceof \DateTime) {
            list($hour, $minute) = explode(':', $this->{$attribute});
            $duration = new \DateInterval("PT{$hour}H{$minute}M");
            $this->_toDateTime = date_add(clone $this->_fromDateTime, $duration);
            $maxToTime = new \DateTime($this->_fromDateTime->format('Y-m-d') . $this->maxHour . ':00');

            if ($this->_toDateTime > $maxToTime) {
                $diff = $this->_fromDateTime->diff($maxToTime)->format("%hч. %iмин.");
                $this->addError($attribute, $this->getAttributeLabel($attribute) . " не может превышать " . $diff);
            }
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->fromTime = new \MongoDate($this->_fromDateTime->getTimestamp());
            $this->toTime = new \MongoDate($this->_toDateTime->getTimestamp());
            $this->rooms = array_map(function ($item) {
                return new \MongoId($item);
            }, $this->rooms);
            return true;
        }
        return false;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'fromTime' => 'Дата и время начала',
            'duration' => 'Продолжительность'
        ]);
    }

    /*public function attributeHints()
    {
        return [
            'fromTime' => 'дата должна быть не больше неде',
            'duration' => 'количество часов и минут'
        ];
    }*/

    /**
     * @return array
     */
    public function getRoomGroups()
    {
        if (!isset($this->_roomGroups)) {

            $pipeline = [
                [
                    '$lookup' => [
                        'from' => Room::collectionName(),
                        'localField' => '_id',
                        'foreignField' => 'groupId',
                        'as' => 'rooms'
                    ]
                ],
                [
                    '$sort' => [
                        'order' => 1
                    ]
                ],
                [
                    '$project' => [
                        'name' => true,
                        'abbreviation' => true,
                        'description' => true,
                        'rooms._id' => true,
                        'rooms.name' => true,
                        'rooms.description' => true,
                        'rooms.bookingAgreement' => true,
                    ]
                ]
            ];

            /** @var Connection $mongodb */
            $mongodb = \Yii::$app->get('mongodb');
            $this->_roomGroups = $mongodb->getCollection(RoomGroup::collectionName())->aggregate($pipeline);
        }

        return $this->_roomGroups;
    }

    public function getBusyRooms($idToString = false)
    {
        $result = [];

        if ($this->validate()) {
            $pipeline = [
                [
                    '$match' => [
                        '_id' => ['$ne' => $this->requestId],
                        'fromTime' => ['$lt' => new \MongoDate($this->_toDateTime->getTimestamp())],
                        'toTime' => ['$gt' => new \MongoDate($this->_fromDateTime->getTimestamp())],
                        'status' => ['$ne' => self::STATUS_CANCEL]
                    ]
                ],
                [
                    '$unwind' => '$rooms'
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => '$rooms',
                        'fromTime' => 1,
                        'toTime' => 1
                    ]
                ]
            ];
            /** @var Connection $mongodb */
            $mongodb = \Yii::$app->get('mongodb');
            $result = $mongodb->getCollection(self::collectionName())->aggregate($pipeline);

            $result = array_map(function ($item) use ($idToString) {
                if ($idToString) {
                    $item['id'] = (string)$item['id'];
                }
                $item['fromTime'] = \Yii::$app->formatter->asTime($item['fromTime']->toDateTime(), 'php:' . self::FORMAT_TIME);
                $item['toTime'] = \Yii::$app->formatter->asTime($item['toTime']->toDateTime(), 'php:' . self::FORMAT_TIME);
                return $item;
            }, $result);
        } else {
            $result = $this->getErrors();
        }
        
        return $result;
    }

}