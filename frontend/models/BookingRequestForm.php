<?php
/**
 * teleport
 * Created: 09.03.16 9:07
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BookingRequestForm
 */

class BookingRequestForm extends BookingRequest
{
    /**
     * @var string
     */
    public $dateString;
    /**
     * @var string
     */
    public $fromTimeString;
    /**
     * @var string
     */
    public $toTimeString;

    public function rules()
    {
        return array_merge(parent::rules(), [
            
        ]);
    }

}