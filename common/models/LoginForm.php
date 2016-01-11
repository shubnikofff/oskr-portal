<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 27.08.15
 * Time: 10:50
 */

namespace common\models;

use yii\base\Model;

/**
 * Class LoginForm
 * @package common\models
 * @property User $user
 */
abstract class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword']
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, "Не верный логин или пароль");
            }
        }
    }

    /**
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return $this->doLogin();
        } else {
            return false;
        }

    }

    /**
     * Finds user by [[username]]
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * @return bool
     */
    protected abstract function doLogin();
}