<?php
/**
 * oskr-portal
 * Created: 28.03.17 14:37
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\vks;

use yii\httpclient\Client;


/**
 * Class ConferenceService
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class ConferenceService
{
    /**
     * @var ConferenceService
     */
    private static $_instance;
    /**
     * @var Client
     */
    private $_httpClient;

    private function __construct()
    {
        $this->_httpClient = new Client([
            'baseUrl' => \Yii::$app->params['mcugw.url'] . '/api/Conference',
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
     * @return ConferenceService
     */
    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param Request $request
     * @param ConferenceForm $form
     * @return \yii\httpclient\Response
     */
    public function create(Request $request, ConferenceForm $form)
    {
        $data = [
            'conferenceName' => $this->generateConferenceName($request),
            'numericId' => $request->conference ? $request->conference->getNumber() : '',
            'pin' => $request->conference ? $request->conference->getPassword() : '',
            'startTime' => $this->generateStartTime($request),
            'endTime' => $this->generateEndTime($request),
            'mcuid' => $form->mcu,
            'profile' => $form->profile,
            'recordType' => $form->audioRecordType,
            'Participants' => $this->generateParticipants($request)

        ];
        return $this->_httpClient->post('', $data)->send();
    }

    /**
     * @param Conference $conference
     * @return \yii\httpclient\Response
     */
    public function destroy(Conference $conference)
    {
        $data = [
            'conferenceName' => $conference->getName(),
            'mcuid' => $conference->getMcuId()
        ];
        return $this->_httpClient->delete('', $data)->send();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function generateConferenceName(Request $request)
    {
        return date('d-m-Y', $request->date->toDateTime()->getTimestamp()) . "_" . $request->number;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function generateStartTime(Request $request)
    {
        return date('Y-m-d', $request->date->toDateTime()->getTimestamp()) . 'T' . $request->beginTimeString . ":00";
    }

    /**
     * @param Request $request
     * @return string
     */
    private function generateEndTime(Request $request)
    {
        return date('Y-m-d', $request->date->toDateTime()->getTimestamp()) . 'T' . $request->endTimeString . ":00";
    }

    private function generateParticipants(Request $request)
    {
        $participants = [];
        $conferenceName = $this->generateConferenceName($request);

        foreach ($request->participants as $participant) {
            $status = $request->getRoomStatus($participant->_id);
            if ($status !== $participant::STATUS_CONSIDIRATION && $status !== $participant::STATUS_CANCEL) {
                $participants[] = [
                    'participantName' => $participant->shortName,
                    'address' => $participant->dialString,
                    'protocol' => $participant->protocol,
                    'conferenceName' => $conferenceName
                ];
            }
        }
        return $participants;
    }
}