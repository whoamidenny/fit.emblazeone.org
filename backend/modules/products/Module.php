<?php

namespace backend\modules\products;

class Module extends \yii\base\Module
{
    public $controllerNamespace;

    public $text = [
        'add_item' => 'Add product',
        'add_item_cat' => 'Add category',
        'add_item_ing' => 'Add ingredient',
        'module_name' => 'Products',
        'module_name_cat' => 'Product categories',
        'module_name_ing' => 'Ingredients',
        'edit_item' => 'Edit product',
        'edit_item_cat' => 'Edit product category',
        'edit_item_ing' => 'Edit ingredient',
        'remove_item' => 'Remove product',
        'remove_confirmation' => 'Do you really want to delete product?',
        'module_manage' => 'Menage products',
        'total_items' => 'Total products',
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
