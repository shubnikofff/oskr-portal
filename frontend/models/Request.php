<?php
/**
 * teleport
 * Created: 03.03.16 11:44
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use common\models\User;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Request model class
 *
 * @property \MongoId $_id
 * @property string $number
 * @property int $status
 * @property null|string $statusName
 * @property User $owner
 * @property \MongoId $createdBy
 * @property \MongoId $updatedBy
 * @property \MongoDate $createdAt
 * @property \MongoDate $updatedAt
 */
abstract class Request extends ActiveRecord
{
    const EVENT_CREATED = 'request_created';
    const EVENT_STATUS_CHANGED = 'status_changed';

    const STATUS_CANCEL = 0;
    const STATUS_AGREED = 1;
    const STATUS_UNDER_CONSIDERATION = 2;
    const STATUS_COMPLETE = 3;

    const NUMBER_PREFIX = '';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->number = $this::NUMBER_PREFIX . date('zH-is');
        }
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return [
            '_id',
            'number',
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
    public function rules()
    {
        return [
            ['status', 'in', 'allowArray' => true,'range' => [self::STATUS_CANCEL, self::STATUS_AGREED, self::STATUS_UNDER_CONSIDERATION, self::STATUS_COMPLETE]]
        ];
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new \MongoDate()
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['_id' => 'createdBy']);
    }

    /**
     * @return null|string
     */
    public function getStatusName()
    {
        switch ($this->status) {
            case self::STATUS_CANCEL:
                return 'Отменено';

            case self::STATUS_AGREED:
                return 'Согласовано';

            case self::STATUS_COMPLETE:
                return 'Выполнено';

            case self::STATUS_UNDER_CONSIDERATION:
                return 'На рассмотрении';

            default:
                return null;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->trigger(self::EVENT_CREATED);
        } elseif (array_key_exists('status', $changedAttributes)) {
            $this->trigger(self::EVENT_STATUS_CHANGED);
        }
    }


}