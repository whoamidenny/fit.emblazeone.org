<?php

namespace backend\modules\faq;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add question',
        'add_item_cat' => 'Add question category',
        'module_name' => 'FAQ',
        'module_name_cat' => 'FAQ categories',
        'edit_item' => 'Edit question',
        'edit_item_cat' => 'Edit category',
        'remove_item' => 'Remove question',
        'remove_confirmation' => 'Do you really want to delete item?',
        'module_manage' => 'Manage FAQ',
        'total_items' => 'Total questions',
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
