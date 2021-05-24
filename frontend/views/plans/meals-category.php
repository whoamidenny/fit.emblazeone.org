<?php
    $bundle=\frontend\assets\AppAsset::register($this);
    if(!$plans) return false;
?>

<div class="col-sm-12">
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
        <div class="weight_2"><?=$category->title?> </div>
    </div>
</div>

<? foreach ($plans as $plan)
    echo $this->render('meals-plan', ['plan'=>$plan]);
?>