<?php
/**
 * teleport
 * Created: 16.10.15 14:19
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\components;
use yii\base\Object;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * MinuteFormatter
 */

class MinuteFormatter extends Object
{
    /**
     * @param int $value
     * @return string
     */
    public static function asString($value)
    {
        $minute = $value % 60;
        $hour = ($value - $minute) / 60;

        return implode(':', [str_pad($hour, 2, '0', STR_PAD_LEFT), str_pad($minute, 2, '0', STR_PAD_LEFT)]);
    }

    /**
     * @param $value
     * @return null|int
     */
    public static function asInt($value)
    {
        list($hour, $minute) = explode(':', $value);
        if (!is_numeric($hour) || !is_numeric($minute)) {
            return null;
        }

        return intval($hour) * 60 + intval($minute);
    }
}