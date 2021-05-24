<?php

namespace backend\modules\accounts;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add user',
        'module_name' => 'Users',
        'edit_item' => 'Edit user',
        'remove_item' => 'Remove user',
        'remove_confirmation' => 'Do you really want to delete item?',
        'module_manage' => 'Manage users',
        'total_items' => 'Total users',
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
