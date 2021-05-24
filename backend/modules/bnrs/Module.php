<?php

namespace backend\modules\bnrs;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Добавить баннер',
        'module_name' => 'Баннеры',
        'edit_item' => 'Редактировать баннер',
        'remove_item' => 'Удалить баннер',
        'remove_confirmation' => 'Вы действительно хотите удалить баннер?',
        'module_manage' => 'Управление баннерами',
        'total_items' => 'Всего баннеров',
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
