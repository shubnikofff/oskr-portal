<?php
/**
 * oskr-portal
 * Created: 07.02.17 14:46
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

use yii\httpclient\Response;


/**
 * Class MCUResponse
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * @property string $errorMessage
 * @property array $conferenceInfo
 */
class MCUResponse extends Response
{
    private $_errorMessage;

    public function getIsOk()
    {
        if (!parent::getIsOk()) {
            $this->_errorMessage = $this->client->baseUrl . " не смог обработать запрос. Код ответа: " . $this->statusCode;
            return false;
        }

        $data = $this->getData();
        if ($data['retcode'] === -1) {
            $this->_errorMessage = "Во время обработки запроса на " . $this->client->baseUrl . " произошла ошибка: " . $data['errorMessage'];
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @return array
     */
    public function getConferenceInfo()
    {
        return parent::getIsOk() ? $this->getData()['Conferences'][0] : [];

    }


}