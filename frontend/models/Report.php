<?php
/**
 * oskr-portal
 * Created: 20.11.2017 9:25
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models;

use frontend\models\vks\Request;
use MongoDB\BSON\Javascript;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\mongodb\Collection;
use yii\mongodb\validators\MongoDateValidator;
use MongoDB\BSON\ObjectId;
use yii\mongodb\validators\MongoIdValidator;

/**
 * Class Report
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class Report extends Model
{

    public $fromDate;
    public $fromMongoDate;
    public $toDate;
    public $toMongoDate;
    public $satisfaction;
    public $employee;

    public function rules()
    {
        return [
            [['fromDate', 'toDate'], 'required'],
            ['fromDate', MongoDateValidator::class, 'format' => 'dd.MM.yyyy', 'mongoDateAttribute' => 'fromMongoDate'],
            ['toDate', MongoDateValidator::class, 'format' => 'dd.MM.yyyy', 'mongoDateAttribute' => 'toMongoDate', 'max' => time(), 'maxString' => \Yii::$app->formatter->asDate(time())],
            ['satisfaction', 'in', 'range' => ['0', '1']],
            ['employee', MongoIdValidator::class]
        ];
    }

    public function attributeLabels()
    {
        return [
            'fromDate' => 'Начальная дата',
            'toDate' => 'Конечная дата дата',
            'satisfaction' => 'Оценка',
            'employee' => 'Сотрудник'
        ];
    }

    public function getCounts()
    {
        /** @var Collection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());

        $map = new Javascript("function(){
                var unsatisfactorily = this.satisfaction < 6 ? 1 : 0;
                emit(null, {'meeting_count':1, 'participants_count':this.participantsId.length, 'unsatisfactorily_count': unsatisfactorily});}"
        );

        $reduce = new Javascript("function(key, values){
                var meetings = 0,
                    participants = 0,
                    unsatisfactorily = 0;
                for(var i in values) {
                    meetings += values[i].meeting_count
                    participants += values[i].participants_count
                    unsatisfactorily += values[i].unsatisfactorily_count
                }
                return {
                    'meeting_count': meetings,
                    'participants_count':participants,
                    'satisfactorily_count': meetings - unsatisfactorily,
                    'unsatisfactorily_count': unsatisfactorily
                }
            }"
        );

        $out = ['inline' => true];

        $condition = [
            'date' => ['$gte' => $this->fromMongoDate, '$lte' => $this->toMongoDate]
        ];
        if (!empty($this->employee)) {
            $condition['createdBy'] = new ObjectID($this->employee);
        }

        $result = $collection->mapReduce($map, $reduce, $out, $condition)[0]['value'];

        $result['satisfactorily_percent'] = round($result['satisfactorily_count'] / $result['meeting_count'] * 100, 2);
        $result['unsatisfactorily_percent'] = round($result['unsatisfactorily_count'] / $result['meeting_count'] * 100, 2);

        return $result;
    }

    public function getDataProvider()
    {
        $query = Request::find()->with('owner');

        $sort = new Sort([
            'attributes' => [
                'date',
                'satisfaction'
            ],
            'defaultOrder' => [
                'date' => SORT_DESC
            ]
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => [
                'pageSize' => '50'
            ]
        ]);

        $query->andWhere(['date' => ['$gte' => $this->fromMongoDate, '$lte' => $this->toMongoDate]]);

        if ($this->satisfaction !== "") {
            $condition = ['satisfaction' => ($this->satisfaction === '0' ? ['$lt' => 6] : ['$not' => ['$lt' => 6]])];
            $query->andWhere($condition);
        }

        if (!empty($this->employee)) {
            $query->andWhere(['createdBy' => new ObjectId($this->employee)]);
        }

        return $dataProvider;
    }

}