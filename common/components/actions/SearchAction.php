<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 14:43
 */

namespace common\components\actions;

use yii\base\InvalidConfigException;
use common\models\SearchModelInterface;
use yii\base\Model;
use yii\helpers\Url;

class SearchAction extends CrudAction
{
    /**
     * @inheritdoc
     */
    public $view = 'index';
    /**
     * @var string
     */
    public $pjaxView;

    public function init()
    {
        if (!is_subclass_of($this->modelClass, 'common\models\SearchModelInterface') && !is_subclass_of($this->modelClass, Model::className())) {
            throw new InvalidConfigException("Property 'modelClass': given class must implement 'common\\models\\SearchModelInterface' and extend '" . Model::className() . "'");
        }
    }

    public function run()
    {
        parent::run();

        Url::remember('',self::URL_NAME_INDEX_ACTION);

        $model = $this->_model;
        $model->load(\Yii::$app->request->get());

        /** @var SearchModelInterface $model */
        $dataProvider = $model->search();

        $viewParams = [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ];

        if (\Yii::$app->request->isPjax && $this->pjaxView !== null) {
            return $this->controller->renderAjax($this->pjaxView, $viewParams);
        }

        return $this->controller->render($this->view, $viewParams);
    }
}