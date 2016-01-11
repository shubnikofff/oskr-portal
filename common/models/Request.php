<?php
/**
 * teleport
 * Created: 05.12.15 9:52
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\models;

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
    /**
     * @event Event
     */
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

        $this->on(self::EVENT_STATUS_CHANGED, [$this, 'onStatusChanged']);
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

    /**
     * @return null|string
     */
    public function getStatusName()
    {
        return self::statusName($this->status);
    }

    /**
     * @param $status
     * @return null|string
     */
    public static function statusName($status)
    {
        $name = null;
        switch ($status) {
            case self::STATUS_CANCEL:
                $name = 'Отменено';
                break;
            case self::STATUS_APPROVE:
                $name = 'Согласовано';
                break;
            case self::STATUS_COMPLETE:
                $name = 'Выполнено';
                break;
            case self::STATUS_CONSIDERATION:
                $name = 'На рассмотрении';
                break;
        };
        return $name;
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['_id' => 'createdBy']);
    }

    public function onStatusChanged($event)
    {
        /** @var Request $request */
        $request = $event->sender;
        $viewHtml = '';
        $viewText = '';
        if ($request instanceof \frontend\models\vks\Request) {
            $viewHtml = 'vksRequestStatusChanged-html';
            $viewText = 'vksRequestStatusChanged-text';
        }

        \Yii::$app->mailer->compose(['html' => $viewHtml, 'text' => $viewText], ['model' => $request])
            ->setFrom([\Yii::$app->params['email.admin'] => 'Служба технической поддрежки' . \Yii::$app->name])
            ->setTo($request->owner->email)
            ->setSubject(\Yii::$app->name . ': изменение статуса заявки')
            ->send();
    }
}