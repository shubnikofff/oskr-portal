<?php
/**
 * teleport
 * Created: 04.12.15 10:40
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\models\user;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Company;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * AccountForm
 */
class AccountForm extends Model
{
    /**
     * @var User
     */
    protected $_user;
    public $username;
    public $currentPassword;
    public $password;
    public $password_repeat;
    public $email;
    public $lastName;
    public $firstName;
    public $middleName;
    public $division;
    public $post;
    public $phone;
    public $mobile;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->_user = Yii::$app->user->identity;
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['username', 'currentPassword', 'password', 'password_repeat', 'email', 'lastName', 'division', 'post', 'phone'], 'required'],

            [['username', 'email'], 'filter', 'filter' => 'trim'],

            ['username', 'string', 'min' => 4, 'max' => 15],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким именем уже зарегистрирован.'],

            ['currentPassword', 'validateCurrentPassword'],

            [['password', 'password_repeat'], 'string', 'min' => 6, 'max' => 20],
            ['password', 'compare'],

            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Данный e-mail уже зарегистрирован.'],

            [['firstName', 'middleName', 'mobile'], 'safe'],
        ];
    }

    public function validateCurrentPassword($attribute)
    {
        if (!$this->_user->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Текущий пароль указан неверно');
        }
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя (логин)',
            'currentPassword' => 'Текущий пароль',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'division' => 'Подразделение',
            'post' => 'Должность',
            'phone' => 'Контактный телефон',
            'mobile' => 'Мобильный телефон',
        ];
    }
}