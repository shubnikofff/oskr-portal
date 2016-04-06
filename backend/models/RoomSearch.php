<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Room;
use yii\mongodb\validators\MongoIdValidator;

/**
 * RoomSearch represents the model behind the search form about `common\models\Room`.
 */
class RoomSearch extends Room
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'safe'],

            [['bookingAgreement', 'multipleBooking'], 'filter', 'filter' => 'boolval'],
            
            ['groupId', MongoIdValidator::className(), 'forceFormat' => 'object']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Room::find()->with('group');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['groupId' => $this->groupId])
            ->andFilterWhere(['bookingAgreement' => $this->bookingAgreement])
            ->andFilterWhere(['multipleBooking'=> $this->multipleBooking]);

        return $dataProvider;
    }
}
