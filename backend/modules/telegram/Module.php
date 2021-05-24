<?php

namespace backend\modules\telegram;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Добавить Пользователи',
        'module_name' => 'Пользователи подключенные к telegram боту',
        'edit_item' => 'Редактировать пользователя',
        'remove_item' => 'Удалить пользователя',
        'remove_confirmation' => 'Вы действительно хотите удалить пользователя?',
        'module_manage' => 'Управление Telegram Бот Пользователями',
        'total_items' => 'Всего Пользователей',
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
