
<?=$this->render('@backend/views/parts/add',[
    'title'=>Yii::$app->controller->module->text['add_item_cat'],
    'form'=>$form
])?>