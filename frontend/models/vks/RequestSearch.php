<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 15:42
 */

namespace frontend\models\vks;

use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\rbac\SystemPermission;
use yii\data\Sort;

class RequestSearch extends Request
{
    const SCENARIO_SEARCH_SCHEDULE = 'search_schedule';
    const SCENARIO_SEARCH_PERSONAL = 'search_personal';

    public function init()
    {
        $this->dateInput = \Yii::$app->formatter->asDate(mktime(0, 0, 0), 'dd.MM.yyyy');
        $this->trigger(self::EVENT_INIT);
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            $this::SCENARIO_SEARCH_SCHEDULE => ['dateInput', 'participantsId'],
            $this::SCENARIO_SEARCH_PERSONAL => ['createdBy']
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['createdBy', 'default', 'value' => \Yii::$app->user->can(SystemPermission::APPROVE_REQUEST) ? null : \Yii::$app->user->identity['primaryKey']]
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Request::find();
        $sort = new Sort([
            'attributes' => [
                'date' => [
                    'asc' => ['date' => SORT_ASC, 'beginTime' => SORT_ASC],
                    'desc' => ['date' => SORT_DESC, 'beginTime' => SORT_DESC],
                ],
                'beginTime'
            ]
        ]);

        $dataProvider = new ActiveDataProvider([
            'sort' => $sort,
            'query' => $query
        ]);


        if ($this->scenario === self::SCENARIO_SEARCH_SCHEDULE) {

            $dataProvider->pagination = false;

            $sort->defaultOrder = [
                'date' => SORT_DESC,
                'beginTime' => SORT_ASC,
            ];
        } elseif ($this->scenario === self::SCENARIO_SEARCH_PERSONAL) {

            $dataProvider->pagination = new Pagination(['pageSize' => 20]);

            $sort->defaultOrder = [
                'date' => SORT_DESC,
            ];
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['date' => $this->date]);

        if (is_array($this->participantsId)) {
            $query->andWhere(['participantsId' => ['$in' => $this->participantsId]]);
        }

        $query->andFilterWhere(['createdBy' => $this->createdBy]);

        return $dataProvider;
    }

}