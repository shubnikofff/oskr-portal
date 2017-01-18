<?php
namespace common\fixtures;

use yii\mongodb\ActiveFixture;

class User extends ActiveFixture
{
    public $modelClass = 'common\models\User';
}