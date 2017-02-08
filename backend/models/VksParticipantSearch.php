<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SearchModelInterface;
use common\models\vks\Participant;
use yii\mongodb\validators\MongoIdValidator;
/**
 * VksParticipantSearch represents the model behind the search form about `common\models\vks\Participant`.
 */
class VksParticipantSearch extends Participant implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['companyId', MongoIdValidator::className(), 'forceFormat' => 'object'],
            [['name', 'shortName', 'dialString', 'note'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Participant::find()->with('company');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'shortName', $this->shortName])
            ->andFilterWhere(['companyId' => $this->companyId])
            ->andFilterWhere(['like', 'dialString', $this->dialString])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
