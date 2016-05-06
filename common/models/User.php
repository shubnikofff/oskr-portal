<?php
namespace common\models;

use yii\base\NotSupportedException;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
use common\components\behaviors\TimestampBehavior;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * User model
 *
 * @property \MongoId $_id
 * @property string $id
 * @property string $username
 * @property string $passwordHash
 * @property string $passwordResetToken
 * @property string $email
 * @property string $activateToken
 * @property string $authKey
 * @property integer $status
 * @property \MongoDate $createdAt
 * @property \MongoDate $updatedAt
 * @property string $password write-only password
 * @property string $statusName
 * @property string $lastName
 * @property string $firstName
 * @property string $middleName
 * @property string $shortName
 * @property string $fullName
 * @property string $division
 * @property string $post
 * @property string $phone
 * @property string $mobile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED = 0;
    const STATUS_NO_ACTIVATE = 1;
    const STATUS_ACTIVE = 10;

    public static function collectionName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    public function attributes()
    {
        return [
            '_id',
            'username',
            'activateToken',
            'authKey',
            'passwordHash',
            'passwordResetToken',
            'email',
            'status',
            'createdAt',
            'updatedAt',
            'lastName',
            'firstName',
            'middleName',
            'division',
            'post',
            'phone',
            'mobile'
        ];
    }

    public function rules()
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED, self::STATUS_NO_ACTIVATE]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'status' => 'Статус',
            'division' => 'Подразделение',
            'post' => 'Должность',
            'phone' => 'Контактный телефон',
            'mobile' => 'Мобильный телефон',
            'createdAt' => 'Дата регистрации'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne(['_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'passwordResetToken' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param $token
     * @return null|User
     */
    public static function findByActivateToken($token)
    {
        return static::findOne(['activateToken' => $token, 'status' => self::STATUS_NO_ACTIVATE]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return (string)$this->_id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->passwordHash = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = \Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->passwordResetToken = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->passwordResetToken = null;
    }

    /**
     * Generates new activate token
     */
    public function generateActivateToken()
    {
        $this->activateToken = \Yii::$app->security->generateRandomString();
    }

    /**
     * Removes activate token
     */
    public function removeActivateToken()
    {
        $this->activateToken = null;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        $result = '';
        switch ($this->status) {
            case self::STATUS_ACTIVE :
                $result = 'Активен';
                break;
            case self::STATUS_BLOCKED :
                $result = 'Заблокирован';
                break;
            case self::STATUS_NO_ACTIVATE :
                $result = 'Не активирован';
                break;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        $result = ucfirst($this->lastName);
        if ($this->firstName) {
            $result .= ' ' . strtoupper(mb_substr($this->firstName, 0, 1)) . '.';
        }
        if ($this->middleName) {
            $result .= strtoupper(mb_substr($this->middleName, 0, 1)) . '.';
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $fullName[] = $this->lastName;
        if ($this->firstName) {
            $fullName[] = $this->firstName;
            if ($this->middleName) {
                $fullName[] = $this->middleName;
            }
        }
        return implode(' ', $fullName);
    }
}