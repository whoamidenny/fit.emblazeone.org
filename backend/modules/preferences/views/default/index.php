<?

use backend\widgets\GridViewAdaptive;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$this->title = Yii::t('admin', Yii::$app->controller->module->text['module_name']);
?>


<header class="page-header">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h1><?=$this->title?></h1>
        </div>
    </div>
</header>


<section class="page-content container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <? $form=ActiveForm::begin(['id'=>'form']);?>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <? foreach ($params as $name=>$param){ ?>
                                    <div class="col-md-6">
                                        <?=$form->field($param['model'], 'value')->textInput(['name'=>'ConfigPar['.$name.']'])->label($param['label'])?>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</section>