<?php

namespace backend\widgets;

use yii\grid\GridView;

class GridViewAdaptive extends GridView
{
    public $dataColumnClass='backend\widgets\DataColumnAdaptive';
    public $tableOptions=['id'=>'sortable', 'class'=>'table adaptive js-table-sortable ui-sortable'];
    public $layout="{items}\n{pager}";
}