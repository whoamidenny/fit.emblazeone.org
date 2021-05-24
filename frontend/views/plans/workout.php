<?php
    $bundle=\frontend\assets\AppAsset::register($this);
?>


<section class="dashboard workout_Plans_bg">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight_2">Your Workout Plan</div>
                    <!--<a href="#" class="btn-all">View All</a>-->
                </div>
            </div>
            <? foreach ($plans as $plan){ ?>
                <div class="col-12 col-sm-4">
                    <a href="<?=$plan->file?$plan->file->getUrl():'#'?>" class="fo_img">
                        <img src="<?=$plan->image->getUrl()?>" alt="">
                    </a>
                    <div class="mb-5 flex-column d-flex justify-content-between align-items-start card-product">
                        <div class="weight font_22 card-product_text"><?=$plan->title?></div>
                        <a href="<?=$plan->file?$plan->file->getUrl():'#'?>" class="btn-all">Download</a>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
</section>
