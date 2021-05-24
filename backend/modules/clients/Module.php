<?php

namespace backend\modules\clients;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add client',
        'module_name' => 'Clients',
        'edit_item' => 'Edit client',
        'remove_item' => 'Remove client',
        'remove_confirmation' => 'Do you really want to delete client?',
        'module_manage' => 'Manage clients',
        'total_items' => 'Total clients',
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
