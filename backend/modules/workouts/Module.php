<?php

namespace backend\modules\workouts;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add workout',
        'module_name' => 'Workouts',
        'edit_item' => 'Edit workout',
        'remove_item' => 'Remove workout',
        'remove_confirmation' => 'Do you really want to delete item?',
        'module_manage' => 'Manage workouts',
        'total_items' => 'Total workouts',
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
