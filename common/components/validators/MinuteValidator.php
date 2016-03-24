<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 10:55
 */

namespace common\components\validators;

use common\components\Minute;
use Yii;
use yii\base\InvalidConfigException;
use yii\validators\NumberValidator;
use yii\validators\Validator;

class MinuteValidator extends Validator
{
    /**
     * @var Minute
     */
    public $min;
    /**
     * @var Minute
     */
    public $max;
    /**
     * @var string
     */
    public $tooBig;
    /**
     * @var string
     */
    public $tooSmall;
    /**
     * @var string
     */
    public $minuteAttribute;

    public function init()
    {
        parent::init();

        if ($this->message === null) {
            $this->message = '{attribute} имеет неверный формат';
        }

        if ($this->max !== null) {
            if (!$this->max instanceof Minute) {
                throw new InvalidConfigException("Property 'max' must be instance of Minute");
            } elseif ($this->tooBig === null) {
                $this->tooBig = '{attribute} должно быть не больше ' . $this->max;
            }
            if (!$this->min instanceof Minute) {
                throw new InvalidConfigException("Property 'min' must be instance of Minute");
            } elseif ($this->tooSmall === null) {
                $this->tooSmall = '{attribute} должно быть не меньше ' . $this->min;
            }
        }
    }

    public function validateAttribute($model, $attribute)
    {
        try {
            $value = new Minute($model->{$attribute});
        } catch (\Exception $e) {
            $this->addError($model, $attribute, $this->message);
            return;
        }

        $numberValidator = new NumberValidator([
            'min' => $this->min->int,
            'max' => $this->max->int,
            'tooBig' => $this->tooBig,
            'tooSmall' => $this->tooSmall
        ]);
        $numberValidator->validate($value->int, $error);

        if ($error !== null) {
            $this->addError($model, $attribute, $error);
        } elseif ($this->minuteAttribute !== null) {
            $model->{$this->minuteAttribute} = $value;
        }
    }
}