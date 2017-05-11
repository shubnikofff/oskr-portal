<?php
/**
 * oskr-portal
 * Created: 11.05.17 12:46
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\audioconference;
use MongoDB\BSON\ObjectID;
use yii\mongodb\ActiveRecord;


/**
 * Class UserAudioConferenceMap
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * @property ObjectID $userId
 * @property string $conferenceId
 */

class UserAudioConferenceMap extends ActiveRecord
{


    public static function collectionName()
    {
        return 'UserAudioConferenceMap';
    }

    public function attributes()
    {
        return [
            '_id',
            'userId',
            'conferenceId'
        ];
    }
}