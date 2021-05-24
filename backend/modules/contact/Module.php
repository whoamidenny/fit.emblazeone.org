<?php

namespace backend\modules\contact;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add User',
        'module_name' => 'Contact',
        'edit_item' => 'Edit',
        'remove_item' => 'Remove',
        'remove_confirmation' => 'Do you really want to delete item?',
        'module_manage' => 'Manage contacts',
        'total_items' => 'Total contacts',
    ];

    public $actions;

    public static $module_actions = [
        'enable_add' => false,
        'enable_edit' => false,
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
