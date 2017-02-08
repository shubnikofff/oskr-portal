<?php
/**
 * oskr-portal
 * Created: 02.02.17 11:31
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

use frontend\models\vks\Request as Meeting;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * Class MCURequest
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
abstract class MCURequest
{

    /**
     * @var Meeting
     */
    protected $_meeting;

    /**
     * @var Client
     */
    protected $_httpClient;

    public function __construct(Meeting $meeting)
    {
        $this->_meeting = $meeting;

        $this->_httpClient = new Client([
            'baseUrl' => 'http://gw.niaepnn.ru/api/Conference',
            'requestConfig' => [
                'format' => Client::FORMAT_JSON,
                'headers' => ['content-type' => 'application/json;charset=utf-8']
            ],
            'responseConfig' => [
                'class' => MCUResponse::class,
                'format' => Client::FORMAT_JSON
            ],
        ]);
    }

    /**
     * @return MCUResponse
     * @throws Exception
     */
    abstract public function send();

}