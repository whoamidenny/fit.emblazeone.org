<?php

use yii\bootstrap4\Modal;

$bundle=\frontend\assets\AppAsset::register($this);
    if(!$plan) return false;
?>

<div class="col-12 col-sm-4">
    <a href="<?=$plan->file?$plan->file->getUrl():'#'?>" class="fo_img">
        <img src="<?=$plan->image->getUrl()?>" alt="">
    </a>
    <div class="mb-5 d-flex flex-column justify-content-between align-items-start card-product">
        <div class="weight font_22 card-product_text"><?=$plan->title?></div>
        <a href="<?=$plan->file?$plan->file->getUrl():'#'?>" class="btn-all">Download</a>
    </div>
</div>
