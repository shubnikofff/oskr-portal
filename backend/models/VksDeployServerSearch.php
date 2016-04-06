<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\vks\DeployServer;

/**
 * VksDeployServerSearch represents the model behind the search form about `common\models\vks\DeployServer`.
 */
class VksDeployServerSearch extends DeployServer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'name'], 'safe'],
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
        $query = DeployServer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);


        return $dataProvider;
    }
}
