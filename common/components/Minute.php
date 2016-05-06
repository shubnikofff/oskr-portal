<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Minute class. Содержит информацию о времени в целочисленном представлении.
 * @property int $int
 */
class Minute
{
    /**
     * Разделитель между минутами и секундами в строковом представлении
     */
    const SEPARATOR = ':';

    /**
     * @var integer
     */
    private $_value;

    /**
     * Minute constructor.
     * @param $value
     * @throws \Exception
     */
    public function __construct($value)
    {
        if (is_int($value) && $value >= 0 && $value < 24 * 60) {
            $this->_value = $value;
        } elseif (is_string($value)) {
            list($hour, $minute) = explode(self::SEPARATOR, $value);
            $hour = intval($hour);
            $minute = intval($minute);
            if ($hour >= 0 && $hour < 24 && $minute >= 0 && $minute < 60) {
                $this->_value = $hour * 60 + $minute;
            }
        }

        if (!isset($this->_value)) {
            throw new \Exception(__METHOD__ . ": given value '{$value}' has wrong format.");
        }
    }

    /**
     * @param $name
     * @return int|null
     */
    function __get($name)
    {
        $value = null;
        if ($name === 'int') {
            $value = $this->_value;
        }
        return $value;
    }

    /**
     * @return string
     */
    function __toString()
    {
        $minute = $this->_value % 60;
        $hour = ($this->_value - $minute) / 60;

        return implode(self::SEPARATOR, [str_pad($hour, 2, '0', STR_PAD_LEFT), str_pad($minute, 2, '0', STR_PAD_LEFT)]);
    }

}