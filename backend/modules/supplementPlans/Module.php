<?php

namespace backend\modules\supplementPlans;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add plan',
        'module_name' => 'Supplement Plans',
        'edit_item' => 'Edit plan',
        'remove_item' => 'Remove plan',
        'remove_confirmation' => 'Do you really want to delete item?',
        'module_manage' => 'Manage plans',
        'total_items' => 'Total plans',
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
