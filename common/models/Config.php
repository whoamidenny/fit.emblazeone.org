<?php
namespace common\models;


use yii\base\Model;

class Config extends Model
{
    public static function getParameter($name, $multilang = true, $default=false)
    {
        if($multilang) $langPrefix=\Yii::$app->lang->getLanguagePrefix();
        else $langPrefix='';

        $value=ConfigPar::get($name . $langPrefix);
        $value1=ConfigPar::get($name);

        if($value!==NULL){
            return $value;
        }elseif($value1!==NULL){
            return $value1;
        }

        $confidPar = ConfigPar::findOne(['name' => $name . $langPrefix]);
        if (!$confidPar || !$confidPar->id) {
            $confidPar = ConfigPar::findOne(['name' => $name]);
        }

        if(!$confidPar) {
            $confidPar=new ConfigPar();
            $confidPar->name=$name . $langPrefix;
            if($default) $confidPar->value=$default;
            $confidPar->save();
        }

        return $confidPar->value;
    }

    public static function setParameter($name, $value)
    {
        $confidPar = ConfigPar::findOne(['name' => $name]);
        $confidPar->value = $value;
        $confidPar->save();
    }


}