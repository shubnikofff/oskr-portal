<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace common\modules\rest\models;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * User
 */

class User extends \common\models\User
{
    public function fields()
    {
        return [
            'id' => function () {
                return (string)$this->_id;
            },
            'email',
            'lastName',
            'firstName',
            'middleName',
            'fullName',
            'shortName',
            'division',
            'post',
            'phone',
            'mobile'
        ];
    }
}