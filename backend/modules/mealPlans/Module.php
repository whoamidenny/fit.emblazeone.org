<?php

namespace backend\modules\mealPlans;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add Meal Plan',
        'module_name' => 'Meal Plans',
        'edit_item' => 'Edit meal plans',
        'remove_item' => 'Remove plan',
        'remove_confirmation' => 'Do you really want to delete item?',
        'module_manage' => 'Manage meal plans',
        'total_items' => 'Total meal plans',
    ];

    public $actions;

    public static $module_actions = [
        'enable_add' => true,
        'enable_edit' => true,
        'enable_delete' => true,
        'enable_view' => true,
    ];

    public function init()
    {
        $this->actions=self::$module_actions;
        $tmp = explode(DIRECTORY_SEPARATOR, __DIR__);
        $tmp = $tmp[count($tmp) - 1];
        $this->controllerNamespace = 'backend\modules\\' . $tmp . '\controllers';

        parent::init();
    }
}
