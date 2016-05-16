<?php
/**
 * teleport
 * Created: 05.12.15 9:52
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\models;

use common\components\helpers\mail\Mailer;
use yii\mongodb\ActiveRecord;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Request
 *
 * @property int $status
 * @property string $statusName
 * @property User $owner
 * @property \MongoDate $createdAt
 * @property \MongoDate $updatedAt
 * @property \MongoId $createdBy
 * @property \MongoId $updatedBy
 */
abstract class Request extends ActiveRecord
{
    const EVENT_STATUS_CHANGED = 'status_changed';

    const STATUS_CANCEL = 0;
    const STATUS_APPROVE = 1;
    const STATUS_CONSIDERATION = 2;
    const STATUS_COMPLETE = 3;

    public function behaviors()
    {
        return [
            'common\components\behaviors\TimestampBehavior',
            'common\components\behaviors\BlameableBehavior',
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

        $mailer = new Mailer();
        $this->on(self::EVENT_STATUS_CHANGED, [$mailer, 'send']);
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_CANCEL, self::STATUS_APPROVE, self::STATUS_CONSIDERATION, self::STATUS_COMPLETE]]
        ];
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['_id' => 'createdBy']);
    }
}