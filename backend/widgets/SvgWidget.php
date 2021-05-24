<?php

namespace backend\widgets;

use yii\helpers\Html;

class SvgWidget extends \yii\base\Widget
{
	public static $usedIcons=[];
    public static $pathToSvg='@app/web/quantum/assets/img/';

    public function run()
    {
//        $svg='<section style="display:none !important">';
//        $svg.='<svg><defs>';
//
//        foreach (self::$usedIcons as $icon){
//            $pathToSvg=\Yii::getAlias($this->pathToSvg).$icon.'.svg';
//            if(!file_exists($pathToSvg)) continue;
//            $svg.='<g id="'.$icon.'">';
//            $svg_icon=file_get_contents($pathToSvg);
//            $svg.=$svg_icon;
//            $svg.='</g>';
//        }
//
//        $svg.='</defs></svg></section>';

        //return $svg;
    }

    public static function getIconByName($icon){
        $pathToSvg=\Yii::getAlias(self::$pathToSvg).$icon.'.svg';
        if(!file_exists($pathToSvg)) return false;
//        $svg.='<g id="'.$icon.'">';
        $svg_icon=file_get_contents($pathToSvg);
        return $svg_icon;
//        $svg.=$svg_icon;
//        $svg.='</g>';
    }

    public static function getSvgIcon($name=''){

        return self::getIconByName($name);

//        if(!in_array($name, self::$usedIcons)){
//            self::$usedIcons[]=$name;
//        }
//        return '
//            <svg viewBox="0 0 100 100" class="svg-icon '.$name.'">
//                <use xlink:href="#'.$name.'"></use>
//            </svg>
//        ';
    }
}
