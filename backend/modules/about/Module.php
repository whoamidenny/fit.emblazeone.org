<?php

namespace backend\modules\about;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Добавить',
        'module_name' => 'О нас',
        'edit_item' => 'Редактировать',
        'remove_item' => 'Удалить',
        'remove_confirmation' => 'Вы действительно хотите удалить?',
        'module_manage' => 'Управление',
        'total_items' => 'Всего',
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
