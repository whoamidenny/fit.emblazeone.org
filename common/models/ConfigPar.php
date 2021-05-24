<?php
namespace common\models;

//    use common\models\Category;

class ConfigPar extends WbpActiveRecord
{
    public static $imageTypes = [];

    public static $configs=[];

    public static function getAll(){
        $all=static::find()->all();
        foreach ($all as $conf){
            self::$configs[$conf->name]=$conf->value;
        }
    }

    public static function get($name){
        if(!count(self::$configs)) {
            static::getAll();
        }

        if(isset(self::$configs[$name])) return static::$configs[$name];
        return NULL;
    }

    public static function tableName()
    {
        return '{{%config}}';
    }

}