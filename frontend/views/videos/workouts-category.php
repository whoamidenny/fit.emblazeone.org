<?php
    $bundle=\frontend\assets\AppAsset::register($this);
    if(!$videos) return false;
?>

    <div class="col-sm-12">
        <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
            <div class="weight_2"><?=$category->title?> </div>
        </div>
    </div>

<? foreach ($videos as $video)
    echo $this->render('workouts-video', ['video'=>$video]);
?>