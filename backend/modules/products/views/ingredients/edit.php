<?=$this->render(
    '@backend/views/parts/edit',
    [
        'title'=>Yii::t('admin', Yii::$app->controller->module->text['edit_item_ing']),
        'form'=>$form,
        'add'=>Yii::$app->controller->module->actions['enable_add']?Yii::t('admin',Yii::$app->controller->module->text['add_item']):false,          // Add "Add button"
        'delete'=>Yii::$app->controller->module->actions['enable_delete']?$model->id:false,                                                 // Add Remove Button
    ])
?>
