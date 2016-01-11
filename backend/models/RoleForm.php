<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 03.09.15
 * Time: 12:51
 */

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\Inflector;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;

/**
 * Class RoleForm
 *
 * @package backend\models
 * @property array $availableRoles
 * @property array $availablePermissions
 *
 */
class RoleForm extends Model
{
    const SCOPE_PARENTS = 0;
    const SCOPE_CHILDREN = 1;
    public $name;
    public $description;
    public $updatedAt;
    public $childRoles = [];
    public $childPermissions = [];

    public function scenarios()
    {
        return [
            'create' => ['name', 'description', 'childRoles', 'childPermissions'],
            'update' => ['description', 'childRoles', 'childPermissions'],
        ];
    }

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['name', 'validateName'],
            [['childRoles', 'childPermissions'], 'filter', 'filter' => function ($value) {
                return empty($value) ? [] : $value;
            }]
        ];
    }

    public function validateName($attribute)
    {
        $name = Inflector::camelize(Inflector::slug($this->$attribute));
        $label = $this->getAttributeLabel($attribute);

        if (empty($name)) {
            $this->addError($attribute, "'$label' должно состоять только из букв и цифр");
        } else {
            $this->$attribute = $name;
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
            'childRoles' => 'Дочерние роли',
            'childPermissions' => 'Дочерние привилегии'
        ];
    }

    /**
     * @return bool
     */
    public function create()
    {
        $authManager = Yii::$app->getAuthManager();
        if ($this->validate()) {
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            if ($authManager->add($role)) {
                $this->addChildren($role);
                return true;
            }
        }
        return false;
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function update(Role $role)
    {
        $authManager = Yii::$app->getAuthManager();
        if ($this->validate()) {
            $role->description = $this->description;
            $authManager->removeChildren($role);
            $this->addChildren($role);
            return $authManager->update($role->name, $role);
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAvailableRoles()
    {
        /** @var DbManager $authManager */
        $authManager = Yii::$app->authManager;
        $roles = (new Query())->select(['name','description'])->from($authManager->itemTable)->where(['type' => Item::TYPE_ROLE])->indexBy('name')->all();
        $parents = Yii::$app->getDb()->createCommand('call relativeItems("'.$this->name.'",'.self::SCOPE_PARENTS.')')->queryAll();

        foreach ($parents as $parent) {
            unset($roles[$parent['item']]);
        }
        unset($roles[$this->name], $roles[Yii::$app->params['admin.role']]);

        return array_map(function($item){return $item['description'];},$roles);
    }

    /**
     * @return array
     */
    public function getAvailablePermissions()
    {
        /** @var DbManager $authManager */
        $authManager = Yii::$app->authManager;
        $systemPermissionsQuery = (new Query())->select(['name','description'])->from($authManager->itemTable)->where(['type' => Item::TYPE_PERMISSION]);
        $childPermissionsQuery = Yii::$app->getDb()->createCommand('call relativeItems("'.$this->name.'",'.self::SCOPE_CHILDREN.')');
        $children = array_map(function($item){return $item['item'];}, $childPermissionsQuery->queryAll());

        $permissions = [];
        foreach ($systemPermissionsQuery->all() as $item) {
            if(!in_array($item['name'], $children) || in_array($item['name'], $this->childPermissions)){
                $permissions[$item['name']] = $item['description'];
            }
        }

        return $permissions;
    }

    /**
     * @param Role $role
     * @return void
     */
    private function addChildren(Role $role)
    {
        $authManager = Yii::$app->getAuthManager();
        foreach ($this->childRoles as $child) {
            $authManager->addChild($role, $authManager->getRole($child));
        }
        foreach ($this->childPermissions as $child) {
            $authManager->addChild($role, $authManager->getPermission($child));
        }
    }

    /**
     * @return void
     */
    public function initChildren()
    {
        if (isset($this->name)) {
            foreach (Yii::$app->authManager->getChildren($this->name) as $child) {
                if ($child instanceof Role) {
                    $this->childRoles[] = $child->name;
                } elseif ($child instanceof Permission) {
                    $this->childPermissions[] = $child->name;
                }
            }
        }
    }
}