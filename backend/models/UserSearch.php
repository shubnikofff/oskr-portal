<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 02.09.15
 * Time: 10:48
 */
namespace backend\models;

use Yii;
use common\models\SearchModelInterface;
use common\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User implements SearchModelInterface
{

    public function rules()
    {
        return [
            [['username', 'email', 'lastName'], 'safe'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED]],
            ['status', 'filter', 'filter' => function ($value) {
                return (int)$value;
            }, 'skipOnEmpty' => true]
        ];
    }

    public function search()
    {
        $query = User::find()->where(['username' => ['$ne' => Yii::$app->params['admin.name']]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'lastName', $this->lastName]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}