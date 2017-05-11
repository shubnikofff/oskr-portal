<?php
/**
 * oskr-portal
 * Created: 11.05.17 12:50
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\audioconference;


/**
 * Class AudioConference
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class AudioConference
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $number;
    /**
     * @var string
     */
    private $pin;
    /**
     * @var string
     */
    private $status;
    /**
     * @var \DateTime
     */
    private $createTime;

    /**
     * AudioConference constructor.
     * @param int $id
     * @param string $number
     * @param string $pin
     * @param string $status
     * @param \DateTime $createTime
     */
    public function __construct($id, $number, $pin, $status, \DateTime $createTime)
    {
        $this->id = $id;
        $this->number = $number;
        $this->pin = $pin;
        $this->status = $status;
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }



}