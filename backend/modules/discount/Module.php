<?php

namespace backend\modules\discount;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Добавить дисконт код',
        'module_name' => 'Дисконт коды',
        'edit_item' => 'Редактировать код',
        'remove_item' => 'Удалить код',
        'remove_confirmation' => 'Вы действительно хотите удалить код?',
        'module_manage' => 'Управление кодами',
        'total_items' => 'Всего кодов',
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
