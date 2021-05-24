<?php

use yii\bootstrap4\Modal;

$bundle=\frontend\assets\AppAsset::register($this);
    if(!$video) return false;
?>

<div class="col-12 col-sm-4">
    <a href="#" onclick="$('#video_modal_<?=$video->id?>').modal('show'); return false;"  class="fo_img">
        <span><i class="fas fa-play"></i></span>
        <img src="<?=$video->image->getUrl("350x185")?>" alt="">
    </a>
    <div class="mb-5 d-flex justify-content-between align-items-start">
        <div class="weight"><?=$video->title?></div>
<!--        <div class="video_col">5 Videos</div>-->
    </div>
</div>

<?php
    Modal::begin([
        'title' => $video->title,
        'size'=>Modal::SIZE_LARGE,
        'id'=>'video_modal_'.$video->id
    ]);
        echo '<div id="video_player_'.$video->id.'"></div>';
        $this->registerJs('
            jwplayer("video_player_'.$video->id.'").setup({ 
                "playlist": [{
                        "file": "'.$video->url.'"
                }]
            });
        ', \yii\web\View::POS_END);
    Modal::end();

?>