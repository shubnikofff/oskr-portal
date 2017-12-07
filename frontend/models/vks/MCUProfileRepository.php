<?php
/**
 * oskr-portal
 * Created: 27.03.17 16:49
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\vks;

use yii\httpclient\Client;

/**
 * Class MCUProfileRepository
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class MCUProfileRepository
{
    /**
     * @var MCUProfileRepository
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
     * @return MCUProfileRepository
     */
    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param string $mcuId
     * @return array
     */
    public function getRaw($mcuId)
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
            $data = $httpClient->get(\Yii::$app->params['mcugw.url'] . '/api/profiles/' . $mcuId, null, ['content-type' => 'application/json;charset=utf-8'])->send()->getData();
            $this->_raw = $data['Profiles'];
        }
        return $this->_raw;
    }
}