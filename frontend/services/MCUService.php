<?php
/**
 * oskr-portal
 * Created: 23.01.17 17:10
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

use common\services\Service;
use frontend\models\vks\Request as Meeting;
use yii\httpclient\Exception;

/**
 * Class MCUService
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class MCUService extends Service
{
    public static function createConference(Meeting &$meeting, array $postData)
    {
        $meeting->scenario = Meeting::SCENARIO_DEPLOY_CONFERENCE;
        $meeting->load($postData);

        if ($response = self::sendRequest(new PostRequest($meeting))) {
            if ($response->isOk) {
                $meeting->mcuId = (string)$response->conferenceInfo['mcuid'];
                $meeting->conferenceId = $response->conferenceInfo['numericId'];
                $meeting->conferencePassword = $response->conferenceInfo['pin'];
                $message = "Создана конференция " . $response->conferenceInfo['conferenceName'] . ". ";
                if ($meeting->save()) {
                    $message .= "Сервер " . $meeting->mcu->name . ". Номер " . $meeting->conferenceId . ". Пароль " . $meeting->conferencePassword . ".";
                } else {
                    $message .= "Данные о конференции не сохранены в заявке из-за следующих ошибок: " . implode(", ", array_values($meeting->getErrors('mcuId')));
                }
                \Yii::$app->session->setFlash('mcu-success', $message);
            } else {
                \Yii::$app->session->setFlash('mcu-error', "Не удалось создать конференцию. " . $response->errorMessage);
            }
        }
    }

    public static function destroyConference(Meeting &$meeting)
    {
        if ($response = self::sendRequest(new DeleteRequest($meeting))) {
            if ($response->isOk) {
                $meeting->mcuId = $meeting->conferenceId = $meeting->conferencePassword = $meeting->audioRecordTypeId = null;
                $meeting->save(false);
                \Yii::$app->session->setFlash('mcu-success', "Конференция разобрана успешно.");
            } else {
                \Yii::$app->session->setFlash('mcu-error', "Не удалось разобрать конференцию. " . $response->errorMessage);
            }
        }
    }

    /**
     * @param MCURequest $request
     * @return bool|MCUResponse
     */
    private static function sendRequest(MCURequest $request)
    {
        try {
            $response = $request->send();
        } catch (Exception $exception) {
            \Yii::$app->session->setFlash('mcu-error', $exception->getName() . ": " . $exception->getMessage());
            return false;
        }

        return $response;
    }

}