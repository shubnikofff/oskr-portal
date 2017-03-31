<?php
/**
 * teleport
 * Created: 05.12.15 9:52
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\models;

use common\components\behaviors\BlameableBehavior;
use common\components\behaviors\TimestampBehavior;
use common\components\helpers\mail\Mailer;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use yii\mongodb\ActiveRecord;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Request
 *
 * @property ObjectID $_id
 * @property int $status
 * @property string $statusName
 * @property User $owner
 * @property UTCDateTime $createdAt
 * @property UTCDateTime $updatedAt
 * @property ObjectID $createdBy
 * @property ObjectID $updatedBy
 */
abstract class Request extends ActiveRecord
{
    const EVENT_STATUS_CHANGED = 'request_status_changed';

    const STATUS_CANCELED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_OSKR_CONSIDERATION = 2;
    const STATUS_COMPLETE = 3;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            '_id',
            'status',
            'createdBy',
            'updatedBy',
            'createdAt',
            'updatedAt'
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_STATUS_CHANGED, [new Mailer(), 'send']);
    }
    
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['_id' => 'createdBy']);
    }
}