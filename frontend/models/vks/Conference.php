<?php
/**
 * oskr-portal
 * Created: 27.03.17 15:26
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\vks;


/**
 * Class Conference
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class Conference
{
    /**
     * @var string
     */
    private $_name;
    /**
     * @var string
     */
    private $_number;
    /**
     * @var string
     */
    private $_password;
    /**
     * @var string
     */
    private $_mcuId;
    /**
     * @var string
     */
    private $_profileId;
    /**
     * @var string
     */
    private $_audioRecordTypeId;
    /**
     * @var string
     */
    private $_externalDS;
    /**
     * @var string
     */
    private $_internalDS;

    /**
     * Conference constructor.
     * @param string $_name
     * @param string $_number
     * @param string $_password
     * @param string $_mcuId
     * @param string $_profileId
     * @param string $_audioRecordTypeId
     * @param string $_externalDS
     * @param string $_internalDS
     */
    public function __construct($_name, $_number, $_password, $_mcuId, $_profileId, $_audioRecordTypeId, $_externalDS, $_internalDS)
    {
        $this->_name = $_name;
        $this->_number = $_number;
        $this->_password = $_password;
        $this->_mcuId = $_mcuId;
        $this->_profileId = $_profileId;
        $this->_audioRecordTypeId = $_audioRecordTypeId;
        $this->_externalDS = $_externalDS;
        $this->_internalDS = $_internalDS;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->_number;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return string
     */
    public function getMcuId()
    {
        return $this->_mcuId;
    }

    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->_profileId;
    }

    /**
     * @return string
     */
    public function getAudioRecordTypeId()
    {
        return $this->_audioRecordTypeId;
    }

    /**
     * @return string
     */
    public function getExternalDS()
    {
        return $this->_externalDS;
    }

    /**
     * @return string
     */
    public function getInternalDS()
    {
        return $this->_internalDS;
    }

}