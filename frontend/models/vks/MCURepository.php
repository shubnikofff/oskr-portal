<?php
/**
 * oskr-portal
 * Created: 27.03.17 15:39
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\vks;

use yii\base\ErrorException;
use yii\httpclient\Client;


/**
 * Class MCURepository
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class MCURepository
{
    /**
     * @var self
     */
    private static $_instance;
    /**
     * @var array
     */
    private $_raw;

    private function __construct()
    {
    }

    /**
     * @return MCURepository
     */
    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @throws ErrorException
     * @return array
     */
    public function getRaw()
    {
        if (!$this->_raw) {
            $httpClient = new Client([
                'requestConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);
            $data = $httpClient->get(\Yii::$app->params['mcugw.url'] . '/api/mcues', null, ['content-type' => 'application/json;charset=utf-8'])->send()->getData();
            $this->_raw = $data['Mcues'];
            if (!is_array($this->_raw)) {
                throw new ErrorException("Не удалось получить список MCU с " . \Yii::$app->params['mcugw.url']);
            }
        }
        return $this->_raw;
    }
}