<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 10:55
 */

namespace common\components\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;
use common\components\MinuteFormatter;

class MinuteValidator extends Validator
{
    public $min;
    public $max;
    public $tooBig;
    public $tooSmall;
    public $minString;
    public $maxString;
    public $minuteAttribute;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', 'The format of {attribute} is invalid.');
        }
        if ($this->min !== null && $this->tooSmall === null) {
            $this->tooSmall = Yii::t('yii', '{attribute} must be no less than {min}.');
        }
        if ($this->max !== null && $this->tooBig === null) {
            $this->tooBig = Yii::t('yii', '{attribute} must be no greater than {max}.');
        }
        if ($this->maxString === null) {
            $this->maxString = (string)$this->max;
        }
        if ($this->minString === null) {
            $this->minString = (string)$this->min;
        }
        if ($this->min !== null && is_string($this->min)) {
            $time = MinuteFormatter::asInt($this->min);
            if ($time === null) {
                throw new InvalidConfigException("Invalid min time value: {$this->min}");
            }
            $this->min = $time;
        }
        if ($this->max !== null && is_string($this->max)) {
            $time = MinuteFormatter::asInt($this->max);
            if ($time === null) {
                throw new InvalidConfigException("Invalid max time value: {$this->max}");
            }
            $this->max = $time;
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->{$attribute};
        $time = MinuteFormatter::asInt($value);
        if ($time === null) {
            $this->addError($model, $attribute, $this->message, []);
        } elseif ($this->min !== null && $time < $this->min) {
            $this->addError($model, $attribute, $this->tooSmall, ['min' => $this->minString]);
        } elseif ($this->max !== null && $time > $this->max) {
            $this->addError($model, $attribute, $this->tooBig, ['max' => $this->maxString]);
        } elseif ($this->minuteAttribute !== null) {
            $model->{$this->minuteAttribute} = $time;
        }
    }
}