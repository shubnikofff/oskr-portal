<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SearchModelInterface;
use common\models\Room;
use yii\mongodb\validators\MongoIdValidator;

/**
 * RoomSearch represents the model behind the search form about `common\models\Room`.
 */
class RoomSearch extends Room implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ipAddress', 'description'], 'safe'],
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
            ->andFilterWhere(['like', 'ipAddress', $this->ipAddress])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
