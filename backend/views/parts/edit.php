<?php
    use yii\helpers\Html;
use yii\helpers\Url;

?>

<header class="page-header">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h1><?=$title?></h1>
        </div>
        <ul class="actions top-right">
            <? if($add){ ?>
                <li class="dropdown">
                    <a href="<?=Url::to(['add'])?>" class="btn btn-fab">
                        <i class="zmdi zmdi-plus zmdi-hc-fw"></i>
                    </a>
                </li>
            <? } ?>
            <? if($delete){?>
                <li class="dropdown">
                    <a href="<?=Url::to(['remove', 'id'=>$delete])?>" class="btn btn-fab">
                        <i class="zmdi zmdi-delete zmdi-hc-fw"></i>
                    </a>
                </li>
            <? } ?>

        </ul>
    </div>
</header>
<section class="page-content container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <?=$form?>
        </div>
    </div>
</section>
