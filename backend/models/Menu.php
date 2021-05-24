<?php
namespace backend\models;
use common\models\Config;
use common\models\Identity;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class Menu extends Model
{
    public static function getMenuItems(){
        $menuItems=[];
        $menuItems[]=[
            'url'=>['/pages/default/index'],
            'class'=>'zmdi zmdi-open-in-browser zmdi-hc-fw',
            'label'=>'Pages',
        ];
        $menuItems[]=[
//            'url' => ['/exercises/default/index'],
            'class'=>'la la-dropbox',
            'label'=>'Exercises',
            'items' => [
                [
                    'url' => ['/exercises/default/index'],
                    'label' => Yii::t('menu', 'Manage'),
                ],
                [
                    'url' => ['/exercisesCategories/default/index'],
                    'label' => Yii::t('menu', 'Categories'),
                ]
            ]
        ];

        $menuItems[]=[
//            'url' => ['/exercises/default/index'],
            'class'=>'zmdi zmdi-landscape zmdi-hc-fw',
            'label'=>'Workouts',
            'items' => [
                [
                    'url' => ['/workouts/default/index'],
                    'label' => Yii::t('menu', 'Manage'),
                ],
                [
                    'url' => ['/workoutsCategories/default/index'],
                    'label' => Yii::t('menu', 'Categories'),
                ]
            ]
        ];
        $menuItems[]=[
//            'url' => ['/exercises/default/index'],
            'class'=>'zmdi zmdi-cocktail zmdi-hc-fw',
            'label'=>'Meal Plans',
            'items' => [
                [
                    'url' => ['/mealPlans/default/index'],
                    'label' => Yii::t('menu', 'Manage'),
                ],
                [
                    'url' => ['/mealCategories/default/index'],
                    'label' => Yii::t('menu', 'Categories'),
                ]
            ]
        ];
        $menuItems[]=[
            'url'=>['/supplementPlans/default/index'],
            'class'=>'la la-picture-o',
            'label'=>'Supplement Plans',
        ];
        $menuItems[]=[
            'url'=>['/workoutsPlans/default/index'],
            'class'=>'zmdi zmdi-map zmdi-hc-fw',
            'label'=>'Workout Plans',
        ];

        $menuItems[]=[
            'url'=>['/clients/default/index'],
            'class'=>'la la-user-secret',
            'label'=>'Clients',
        ];

        $menuItems[]=[
            'url'=>['/preferences/default/index'],
            'class'=>'la la-cog',
            'label'=>'Settings',
        ];
        return $menuItems;
    }

}
