<?php

namespace backend\modules\testimonials;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Добавить отзыв',
        'module_name' => 'Отзывы',
        'edit_item' => 'Редактировать отзыв',
        'remove_item' => 'Удалить отзыв',
        'remove_confirmation' => 'Вы действительно хотите удалить отзыв?',
        'module_manage' => 'Управление отзывами',
        'total_items' => 'Всего отзывов',
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
