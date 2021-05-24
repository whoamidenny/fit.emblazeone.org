<?php


namespace backend\widgets;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class DataColumnAdaptive extends DataColumn
{

    public function getHeaderCellLabel()
    {
        $provider = $this->grid->dataProvider;

        if ($this->label === null) {
            if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
                /* @var $modelClass Model */
                $modelClass = $provider->query->modelClass;
                $model = $modelClass::instance();
                $label = $model->getAttributeLabel($this->attribute);
            } elseif ($provider instanceof ArrayDataProvider && $provider->modelClass !== null) {
                /* @var $modelClass Model */
                $modelClass = $provider->modelClass;
                $model = $modelClass::instance();
                $label = $model->getAttributeLabel($this->attribute);
            } elseif ($this->grid->filterModel !== null && $this->grid->filterModel instanceof Model) {
                $label = $this->grid->filterModel->getAttributeLabel($this->attribute);
            } else {
                $models = $provider->getModels();
                if (($model = reset($models)) instanceof Model) {
                    /* @var $model Model */
                    $label = $model->getAttributeLabel($this->attribute);
                } else {
                    $label = Inflector::camel2words($this->attribute);
                }
            }
        } else {
            $label = $this->label;
        }

        if(is_callable($this->contentOptions)) {
            $callable=$this->contentOptions;

            $this->contentOptions=function($data) use($callable, $label){
                return ArrayHelper::merge($callable($data), ['title'=>$label]);
            };
        }elseif(!is_array($this->contentOptions)){
            $this->contentOptions=[
                'title'=>$label
            ];
        }else{
            $this->contentOptions['title']=$label;
        }

        return $label;
    }
}