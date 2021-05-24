<?php

namespace backend\modules\orders;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Добавить заказ',
        'module_name' => 'Заказы',
        'edit_item' => 'Править заказ',
        'remove_item' => 'Удалить',
        'remove_confirmation' => 'Are you sure want to delete this item?',
        'module_manage' => 'Управление заказами',
        'total_items' => 'Всего заказов',
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
