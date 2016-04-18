<?php
/**
 * teleport
 * Created: 04.03.16 9:55
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models;
use common\components\Minute;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BookingRequest
 *
 * @property \MongoDate $date
 * @property Minute $fromTime
 * @property Minute $toTime
 * @property array $rooms
 * @property string $meetingTopic
 * @property array $options
 * @property string $cancellationReason
 * @property string $note
 */

class BookingRequest extends Request
{
    const NUMBER_PREFIX = 'Б';

    const OPTION_VKS = 'vks';
    const OPTION_AUDIO_RECORD = 'audio_record';
    const OPTION_PROJECTOR = 'projector';
    const OPTION_SCREEN = 'screen';

    /**
     * @inheritDoc
     */
    public static function collectionName()
    {
        return 'request.booking';
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(),[
            'date',
            'fromTime',
            'toTime',
            'rooms',
            'eventPurpose',
            'options',
            'cancellationReason',
            'note'
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['options', 'in', 'range' => [self::OPTION_VKS, self::OPTION_AUDIO_RECORD, self::OPTION_PROJECTOR, self::OPTION_SCREEN], 'allowArray' => true]
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'date' => 'Дата',
            'fromTime' => 'с',
            'toTime' => 'по',
            'rooms' => 'Помещения',
            'eventPurpose' => 'Цель мероприятия',
            'options' => 'Опции',
            'cancellationReason' => 'Причина отмены',
            'note' => 'Примечание'
        ]);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->fromTime = new Minute($this->fromTime);
        $this->toTime = new Minute($this->toTime);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->fromTime = $this->fromTime->int;
            $this->toTime = $this->toTime->int;
            return true;
        }
        return false;
    }


}