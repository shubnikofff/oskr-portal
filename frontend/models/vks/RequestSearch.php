<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 15:42
 */

namespace frontend\models\vks;

use common\models\SearchModelInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\rbac\SystemPermission;
use yii\data\Sort;

class RequestSearch extends Request implements SearchModelInterface
{
    const SCENARIO_SEARCH_SCHEDULE = 'search_schedule';
    const SCENARIO_SEARCH_PERSONAL = 'search_personal';

    public $searchKey;

    public function init()
    {
        $this->dateInput = \Yii::$app->formatter->asDate(mktime(0, 0, 0), 'dd.MM.yyyy');
        $this->trigger(self::EVENT_INIT);
        $this->mode = '';
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            $this::SCENARIO_SEARCH_SCHEDULE => ['dateInput', 'participantsId', 'number', 'mode'],
            $this::SCENARIO_SEARCH_PERSONAL => ['createdBy', 'searchKey']
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['createdBy', 'default', 'value' => \Yii::$app->user->can(SystemPermission::APPROVE_REQUEST) ? null : \Yii::$app->user->identity['primaryKey']],
            [['number', 'mode'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
            ['searchKey', 'safe']
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

        $query->andFilterWhere(['mode' => $this->mode]);

        $query->andFilterWhere(['date' => $this->date]);

        $query->andFilterWhere(['number' => $this->number]);

        if (is_array($this->participantsId)) {
            $query->andWhere(['participantsId' => ['$in' => $this->participantsId]]);
        }

        if ($this->searchKey !== null) {
            $query->andWhere(['$or' => [
                ['topic' => ['$regex' => $this->searchKey, '$options' => 'i']],
                ['note' => ['$regex' => $this->searchKey, '$options' => 'i']]
            ]]);
        }

        $query->andFilterWhere(['createdBy' => $this->createdBy]);

        return $dataProvider;
    }

    /**
     * @return ActiveDataProvider
     */
    public function getWithoutFeedbackList()
    {
        $query = self::find()->where([
            'createdBy' => new ObjectId(\Yii::$app->user->id),
            'satisfaction' => ['$exists' => false],
            'date' => ['$lt' => new UTCDateTime()]
        ]);

        $sort = new Sort([
            'attributes' => [
                'date',
                'status'
            ],
            'defaultOrder' => [
                'date' => SORT_DESC
            ]
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort
        ]);

        return $dataProvider;
    }
}