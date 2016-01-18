<?php
namespace console\controllers;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\db\IntegrityException;
use yii\rbac\DbManager;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii\validators\StringValidator;
use common\models\User;

/**
 * Создает учетные записи пользователей, роли и привилегии системы по умолчанию.
 */
class RbacController extends Controller
{
    const TYPE_ROLE = 'role';
    const TYPE_PERMISSION = 'permission';
    const TYPE_RULE = 'rule';

    /**
     * @var DbManager
     */
    private $authManager;

    public function init()
    {
        parent::init();
        $this->authManager = $this->getAuthManager();
    }

    /**
     * Инициализирует систему настройками безопасности
     * @return int
     */
    public function actionInit()
    {
        try {
            $this->setDefault();
        } catch (IntegrityException $e) {
            $answer = $this->prompt('В системе уже имеются некоторые настройки безопасности. Желаете сбросить их по умолчанию? (y/n)', [
                'default' => 'n',
                'validator' => function ($input) {
                    return in_array($input, ['y', 'n']);
                }
            ]);
            if ($answer === 'n') {
                return Controller::EXIT_CODE_ERROR;
            }
            $this->actionDefault();
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Сбрасывает настройки безопасности системы по умолчанию
     * @return int
     */
    public function actionDefault()
    {
        $answer = $this->prompt('Все текущие настройки будут удлаены. Продолжить? (y/n)', [
            'default' => 'n',
            'validator' => function ($input) {
                return in_array($input, ['y', 'n']);
            }
        ]);

        if ($answer === 'n') {
            return Controller::EXIT_CODE_NORMAL;
        }

        $this->authManager->removeAll();
        $this->setDefault();

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Добавляет роль, привилегию или правило в систему
     * @param string $type Тип объекта (role|permission|rule)
     * @param string $name Имя привилегии|роли или имя класса правила
     * @param null $ruleName Имя правила, которое нужно добавить к роли или привилегии
     * @param string $description Описание
     * @return int
     */
    public function actionCreate($type, $name, $description = '', $ruleName = null)
    {
        switch($type)
        {
            case self::TYPE_ROLE: $this->createRole($name, $description, $ruleName);break;
            case self::TYPE_PERMISSION: $this->createPermission($name, $description, $ruleName);break;
            case self::TYPE_RULE: $this->createRule($name);break;
            default: throw new InvalidParamException(self::invalidTypeMessage()); break;
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Добавляет дочернюю привелегию
     *
     * @param string $parentType
     * @param string $parent
     * @param string $child
     */
    public function actionAddChildPermission($parentType, $parent, $child)
    {
        $authManager = Yii::$app->authManager;

        switch($parentType)
        {
            case self::TYPE_ROLE: $parent = $authManager->getRole($parent);break;
            case self::TYPE_PERMISSION: $parent = $authManager->getPermission($parent);break;
            default: throw new InvalidParamException(self::invalidTypeMessage()); break;
        }

        $child = $authManager->getPermission($child);

        $authManager->addChild($parent, $child);

    }

    /**
     * Удаляет роль, привилегию или правило из системы
     * @param $type
     * @param $name
     * @return int
     * @throws ErrorException
     */
    public function actionDelete($type, $name)
    {
        $item = null;
        switch($type)
        {
            case self::TYPE_ROLE: $item = $this->authManager->getRole($name);break;
            case self::TYPE_PERMISSION: $item = $this->authManager->getPermission($name);break;
            case self::TYPE_RULE: $item = $this->authManager->getRule($name);break;
            default: throw new InvalidParamException(self::invalidTypeMessage()); break;
        }

        if (is_null($item)) {
            throw new ErrorException("$type '$name' не найдена.");
        }

        $this->authManager->remove($item);

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @param $name
     * @param $description
     * @param $ruleName
     * @return Role
     */
    private function createRole($name, $description = '', $ruleName = null)
    {
        $role = new Role([
            'name' => $name,
            'description' => $description,
            'ruleName' => $ruleName
        ]);
        $this->authManager->add($role);
        return $role;
    }

    /**
     * @param $name
     * @param $description
     * @param $ruleName
     * @return Permission
     */
    private function createPermission($name, $description = '', $ruleName = null)
    {
        $permission = new Permission([
            'name' => $name,
            'description' => $description,
            'ruleName' => $ruleName
        ]);
        $this->authManager->add($permission);
        return $permission;
    }

    /**
     * @param $className
     * @return Rule
     */
    private function createRule($className)
    {
        $rule = new $className;
        $this->authManager->add($rule);
        return $rule;
    }

    private function setDefault()
    {
        $userPermission = $this->createPermission('editUser', 'Изменение пользователей системы');
        $rolePermission = $this->createPermission('editRole', 'Изменение ролей пользователей');
        $adminRole = $this->createRole(Yii::$app->params['admin.role'], 'Администратор');

        $this->authManager->addChild($adminRole, $userPermission);
        $this->authManager->addChild($adminRole, $rolePermission);

        $admin = User::findOne(['username' => Yii::$app->params['admin.name']]);
        if (is_null($admin)) {
            $admin = $this->createAdminUser();
        }
        $this->authManager->assign($adminRole, $admin->getPrimaryKey());
    }

    /**
     * @return User
     */
    private function createAdminUser()
    {
        $password = $this->prompt("Введите пароль суперпользователя системы:", [
            'required' => true,
            'validator' => function ($input) {
                $validator = new StringValidator(['min' => 6]);
                return $validator->validate($input);
            }
        ]);
        $user = new User();
        $user->username = Yii::$app->params['admin.name'];
        $user->setPassword($password);
        $user->email = Yii::$app->params['admin.email'];
        $user->generateAuthKey();
        $user->save();
        return $user;
    }

    /**
     * @return DbManager
     * @throws InvalidConfigException
     */
    private function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException("Сначала необходимо задать конфигурацию компонента приложения \"authManager\"");
        }
        return $authManager;
    }

    /**
     * @return string
     */
    private static function invalidTypeMessage()
    {
        return "Неверно указан параметр type. Допустимые значения: ".self::TYPE_ROLE."|".self::TYPE_PERMISSION."|".self::TYPE_RULE;
    }
}