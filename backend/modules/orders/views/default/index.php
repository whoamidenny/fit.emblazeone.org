<?

use backend\modules\orders\models\Orders;
use backend\modules\products\models\Products;
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
        <ul class="actions top-right">
            <li class="dropdown">
                <? if(Yii::$app->controller->module->actions['enable_add']){ ?>
                        <a href="<?=Url::to(['add'])?>" class="btn btn-fab">
                            <i class="zmdi zmdi-plus zmdi-hc-fw"></i>
                        </a>
                <? } ?>
            </li>
        </ul>
    </div>
</header>


<section class="page-content container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <?=$this->render('filter',['searchModel'=>$searchModel])?>
        </div>



        <div class="col-xl-12">
            <div class="card">
                <div class="card-body p-0">
                <?
                    Pjax::begin(['id'=>'pjax-owners']);

                    $colums=[
                        'id',
                        [
                            'label' => 'Пользователь',
                            'format'=>'raw',
                            'attribute'=>'name',
                            'value' => function ($data) {
                                return $data->name.'<br />'.$data->phone.'<br />'.$data->email;
                            }
                        ],
                        [
                            'label' => 'Заказ',
                            'format'=>'raw',
                            'attribute'=>'status',
                            'value' => function ($data) {
                                $result="";
                                foreach ($data->orderItems as $orderItem){
                                    $result.=$orderItem->title." (".Products::getLengthsDays()[$orderItem->size].")<br />";
                                }
                                return $result;
                            }
                        ],
                        [
                            'label' => 'Доставка',
                            'format'=>'raw',
                            'attribute'=>'street',
                            'value' => function ($data) {
                                $result=$data->street.' '.$data->house.', '.$data->appart;
                                if($data->entrance) $result.=', Подъезд: '.$data->entrance;
                                if($data->key_code) $result.=', Домофон: '.$data->key_code;
                                $result.='<br /><b>Дата: '.$data->date.'<br /> Время: '.Orders::$times[$data->time].'</b>';
                                return $result.'<br />'.$data->notes;
                            }
                        ],
                        [
                            'label' => 'Сумма',
                            'format'=>'raw',
                            'attribute'=>'amount',
                            'value' => function ($data) {
                                $result = $data->amount."&nbsp;грн <br />";
                                if($data->discount>0) $result .= "Скидка: ".$data->discount."&nbsp;грн<br /> (".$data->discount_code.")";
                                return $result;
                            }
                        ],
                        [
                            'label' => 'Статус',
                            'format'=>'raw',
                            'attribute'=>'status',
                            'value' => function ($data) {
                                return Orders::$statuses[(int)$data->status];
                            }
                        ],
                        [
                            'label' => 'Оплата',
                            'format'=>'raw',
                            'attribute'=>'status',
                            'contentOptions'=>['style'=>'width:100px;'],
                            'value' => function ($data) {
                                return Orders::$payment_types[$data->payment_type].'<br />'.Orders::$payment_statuses[(int)$data->payment_status];
                            }
                        ],
                    ];

                    if(Yii::$app->controller->module->actions['enable_delete'] || Yii::$app->controller->module->actions['enable_edit']) {
                        $colums[] = [
                            'label' => 'Действие',
                            'format' => 'raw',
                            'attribute' => 'id',
                            'value' => function ($data) {
                                return "
                                    <div class=\"btn-group dropdown\">
                                        <button type=\"button\" class=\"btn btn-info btn-outline dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">Действия</button>
                                        <div class=\"dropdown-menu\" x-placement=\"top-start\">
                                            " . (!Yii::$app->controller->module->actions['enable_delete']?'':\yii\helpers\Html::a('Редактировать', ['edit', 'id' => $data->id], ['data' => ["pjax" => 0], "class" => "dropdown-item"]) ). "
                                            " . (!Yii::$app->controller->module->actions['enable_delete']?'':\yii\helpers\Html::a('Удалить', ['delete', 'id' => $data->id], ['data' => ["pjax" => 0], "class" => "dropdown-item removeItem"]) ). "
                                        </div>
                                    </div>
                                ";
                            }
                        ];
                    }

                    echo GridViewAdaptive::widget([
                        'dataProvider' => $dataProvider,
//                        'rowOptions' => function ($data, $index, $widget, $grid){
//                            if($data->status==\app\models\Identity::STATUS_NEW)
//                                return ['style'=>'color:#ff0000;'];
//                            else
//                                return [];
//                        },
                        'columns' => $colums,
                    ]);

                    Pjax::end();

                ?>
                </div>
            </div>
        </div>
    </div>
</section>