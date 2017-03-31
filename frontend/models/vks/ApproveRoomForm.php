<?php
/**
 * oskr.local
 * Created: 18.05.16 12:51
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\vks;

use common\components\events\RoomStatusChangedEvent;
use common\components\MinuteFormatter;
use common\models\vks\Participant;
use MongoDB\BSON\ObjectID;
use yii\base\Model;
use yii\helpers\Html;
use yii\mongodb\Collection;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ApproveRoomForm
 */
class ApproveRoomForm extends Model
{
    const SCENARIO_CANCEL_ROOM = 'cancel_room';

    /**
     * @var Request
     */
    public $request;
    /**
     * @var string
     */
    public $approvedRoomId;

    public function rules()
    {
        return [
            ['approvedRoomId', 'required'],

            ['approvedRoomId', 'exist', 'targetClass' => Participant::class, 'targetAttribute' => '_id'],

            /** Проверка на пересечение с другими совещаниями */
            ['approvedRoomId', function ($attribute) {
                /** @var Collection $collection */
                $collection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());
                /** @var Request $request */
                $request = $collection->findOne([
                    '_id' => ['$ne' => $this->request->_id],
                    'date' => $this->request->date,
                    'beginTime' => ['$lt' => $this->request->endTime],
                    'endTime' => ['$gt' => $this->request->beginTime],
                    'status' => ['$ne' => Request::STATUS_CANCELED],
                    'participantsId' => new ObjectID($this->{$attribute})
                ]);

                if ($request !== null) {
                    $this->addError($attribute, "Данное помещение забронировано для " . Html::a("другого совещания", ['vks-request/view', 'id' => (string)$request['_id']]) .
                        " c " . MinuteFormatter::asString($request['beginTime']) . " по " . MinuteFormatter::asString($request['endTime']));
                }
            }, 'except' => self::SCENARIO_CANCEL_ROOM]
        ];
    }

    private function saveRoomStatus(ObjectID $roomId, $status, $newRecord = false)
    {
        /** @var Collection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Participant::collectionName());

        if ($newRecord) {
            $count = $collection->update(['_id' => $roomId], ['$addToSet' => ['log' => [
                'requestId' => $roomId,
                'status' => $status
            ]]]);
        } else {
            $count = $collection->update([
                '_id' => $roomId,
                'log' => ['$elemMatch' => ['requestId' => $this->request->_id]]
            ], [
                '$set' => ['log.$.status' => $status]
            ]);
        }

        if ($count !== false && $count > 0) {
            /** Это ваще жесть !!! Так делать никогда больше нельзя! */
            Participant::findOne(['_id' => $roomId])->trigger(Participant::EVENT_STATUS_CHANGED, new RoomStatusChangedEvent([
                'request' => $this->request,
                'roomStatus' => $status
            ]));
        }
    }

    public function approveRoom($roomId)
    {
        $request = $this->request;
        $roomMongoId = new ObjectID($roomId);
        $approvedRoomMongoId = new ObjectID($this->approvedRoomId);
        /** @var Collection $participantCollection*/
        $participantCollection = \Yii::$app->get('mongodb')->getCollection(Participant::collectionName());
        /** @var Collection $requestCollection*/
        $requestCollection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());
        $newLogRecord = false;

        if ($this->approvedRoomId !== $roomId) {
            $key = array_search($roomMongoId, $request->participantsId);
            /** Если меняется комната в заявке */
            if ($key !== false) {
                $requestCollection->update([
                    '_id' => $request->_id,
                    'participantsId' => $roomMongoId
                ], ['$set' => ['participantsId.$' => $approvedRoomMongoId]]);
                $participantCollection->update(['_id' => $roomMongoId], ['$pull' => ['log' => ['requestId' => $request->_id]]]);

                \Yii::$app->mailer->compose(['html' => 'changeRoom'], [
                        'request' => $request,
                        'oldRoom' => Participant::findOne(['_id' => $roomMongoId]),
                        'newRoom' => Participant::findOne(['_id' => $approvedRoomMongoId])
                    ])
                ->setFrom([\Yii::$app->params['email.admin'] => 'Служба технической поддержки ' . \Yii::$app->name])
                ->setTo($request->owner->email)
                ->setSubject("Изменение помещения в заявке")
                ->send();

                $newLogRecord = true;
            }
        }
        $this->saveRoomStatus($approvedRoomMongoId, Participant::STATUS_APPROVE, $newLogRecord);
    }

    public function cancelRoom($roomId)
    {
        $this->saveRoomStatus(new ObjectID($roomId), Participant::STATUS_CANCEL);
    }

    public function attributeLabels()
    {
        return ['approvedRoomId' => 'Помещение'];
    }

    public static function roomsList()
    {
        /** @var Collection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Participant::collectionName());
        $cursor = $collection->find(['confirmPersonId' => \Yii::$app->user->identity['_id']], ['name' => 1]);
        $rooms = [];

        foreach ($cursor as $document) {
            $rooms[(string)$document['_id']] = $document['name'];
        }

        return $rooms;
    }
}