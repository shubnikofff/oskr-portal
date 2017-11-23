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
use MongoDB\BSON\ObjectID;


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
            ['toDate', MongoDateValidator::class, 'format' => 'dd.MM.yyyy', 'mongoDateAttribute' => 'toMongoDate'],
            ['satisfaction', 'in', 'range' => ['0', '1']],
            ['employee', 'safe']
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
                var unsatisfactorily = this.satisfaction === '0' ? 1 : 0;
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
            ]
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort
        ]);

        $query->andWhere(['date' => ['$gte' => $this->fromMongoDate, '$lte' => $this->toMongoDate]]);

        if($this->satisfaction !== "") {
            $query->andWhere(['satisfaction' => ($this->satisfaction === '0' ? '0' : ['$ne' => '0'])]);
        }

        if(!empty($this->employee)) {
            $query->andWhere(['createdBy' =>  new ObjectID($this->employee)]);
        }

        return $dataProvider;
    }

}