<?php
/**
 * oskr-portal
 * Created: 11.05.17 12:58
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\audioconference;

use MongoDB\BSON\ObjectID;
use yii\httpclient\Client;


/**
 * Class AudioConferenceService
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class AudioConferenceService
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'baseUrl' => 'http://gw.niaepnn.ru/api/Meetme',
            'requestConfig' => [
                'format' => Client::FORMAT_JSON,
                'headers' => ['content-type' => 'application/json;charset=utf-8']
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
    }

    /**
     * @param $userId
     * @return AudioConference|null
     */
    public function getConferenceByUserId($userId)
    {
        $conferenceId = UserAudioConferenceMap::find()->where(['userId' => $userId])->orderBy(['conferenceId' => SORT_DESC])->one()->conferenceId;
        if ($conferenceId) {
            $responseData = $this->httpClient->get($conferenceId)->send()->getData();
            if ($responseData['retcode'] == 100) {
                return $this->makeAudioConference($responseData['meetme'][0]);
            }
        }
        return null;


    }

    /**
     * @param $userId
     * @return AudioConference|null
     */
    public function createConferenceForUser($userId)
    {
        $responseData = $this->httpClient->post('', '{}')->send()->getData();
        if ($responseData['retcode'] == 100) {
            $conference = $this->makeAudioConference($responseData['meetme'][0]);
            (new UserAudioConferenceMap(['userId' => $userId, 'conferenceId' => $conference->getId()]))->save();
            return $conference;
        } else {
            \Yii::$app->session->setFlash('error', $responseData['errorMessage']);
            return null;
        }
    }

    /**
     * @param $userId
     */
    public function deleteConferenceForUser($userId)
    {
        $conferenceId = UserAudioConferenceMap::findOne(['userId' => $userId])->conferenceId;
        $responseData = $this->httpClient->delete('', ['id' => $conferenceId])->send()->getData();
        if ($responseData['retcode'] == 100) {
            UserAudioConferenceMap::deleteAll(['userId' => $userId]);
        } else {
            \Yii::$app->session->setFlash('error', $responseData['errorMessage']);
        }
    }

    /**
     * @param $row
     * @return AudioConference
     */
    private function makeAudioConference($row)
    {
        return new AudioConference($row['id'], $row['numericid'], $row['pin'], $row['status'], new \DateTime($row['dttm']));
    }
}