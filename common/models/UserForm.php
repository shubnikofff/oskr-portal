<?php
namespace common\models;

use MongoDB\BSON\ObjectID;
use Yii;
use yii\base\Model;

/**
 * Форма регистрации пользователя системы
 * @property array $availableRoles
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $lastName;
    public $firstName;
    public $middleName;
    public $division;
    public $post;
    public $phone;
    public $mobile;
    public $roles = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким именем уже зарегистрирован.'],
            ['username', 'string', 'min' => 4, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данный e-mail уже используется.', 'filter' => ['username' => ['$ne' => $this->username]]],

            [['password', 'password_repeat'], 'required'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            ['password', 'compare'],

            [['lastName', 'division', 'post', 'phone'], 'required'],
            [['firstName', 'middleName', 'mobile', 'roles'], 'safe'],

            ['roles', 'filter', 'filter' => function ($value) {
                return empty($value) ? [] : $value;
            }]
        ];
    }

    public function scenarios()
    {
        return [
            'signup' => ['username', 'email', 'password', 'password_repeat', 'lastName', 'firstName', 'middleName', 'division', 'post', 'phone', 'mobile'],
            'update' => ['email', 'lastName', 'firstName', 'middleName', 'division', 'post', 'phone', 'mobile', 'roles'],
            'update-profile' => ['email', 'lastName', 'firstName', 'middleName', 'division', 'post', 'phone', 'mobile'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя (логин)',
            'password' => 'Пароль',
            'password_repeat' => 'Повторно пароль',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'division' => 'Подразделение',
            'post' => 'Должность',
            'phone' => 'Внутренний телефон',
            'mobile' => 'Мобильный телефон',
            'roles' => 'Роли'
        ];
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->lastName = $this->lastName;
            $user->firstName = $this->firstName;
            $user->middleName = $this->middleName;
            $user->division = $this->division;
            $user->post = $this->post;
            $user->phone = $this->phone;
            $user->mobile = $this->mobile;
            if ($user->save()) {
                $authManager = Yii::$app->authManager;
                $employeeRole = $authManager->getRole(Yii::$app->params['employee.role']);
                $authManager->assign($employeeRole, $user->id);
                return $user;
            }
        }
        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        if ($this->validate()) {
            $user->email = $this->email;
            $user->lastName = $this->lastName;
            $user->firstName = $this->firstName;
            $user->middleName = $this->middleName;
            $user->division = $this->division;
            $user->post = $this->post;
            $user->phone = $this->phone;
            $user->mobile = $this->mobile;
            return $user->save();
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAvailableRoles(){
        $roles = [];
        foreach (Yii::$app->authManager->getRoles() as $role) {
            $roles[$role->name] = $role->description;
        }
        return $roles;
    }

    /**
     * @param ObjectID|string $id
     */
    public function initRoles($id){
        foreach (Yii::$app->authManager->getRolesByUser($id) as $role) {
            $this->roles[] = $role->name;
        }
    }

    /**
     * @param ObjectID|string $id
     */
    public function assignRoles($id){
        $authManager = Yii::$app->getAuthManager();
        $authManager->revokeAll($id);
        foreach ($this->roles as $roleName) {
            $authManager->assign($authManager->getRole($roleName), $id);
        }
    }
}
