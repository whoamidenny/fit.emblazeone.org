<?php

use common\models\Config;
use yii\bootstrap4\Modal;
use yii\web\View;

?>

<footer>
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-sm-4 text-left text_fot_1">
                #reinventyourself
                <a href="<?=\common\models\Config::getParameter('facebook')?>" title="facebook" target="_blank" class="facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="<?=\common\models\Config::getParameter('twitter')?>" title="twitter" target="_blank" class="twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="<?=\common\models\Config::getParameter('instagram')?>" title="instagram" target="_blank" class="instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
            <div class="col-sm-4 text-center">
                <p class="copy">
                    <?=\common\models\Config::getParameter('copyright')?>
                </p>
            </div>
            <div class="col-sm-4 text-right link_terms">
                <a href="#" onclick="$('#terms_modal').modal('show'); return false;" target="_blank">
                    Terms & Conditions
                </a>
                <a href="#" onclick="$('#privacy_modal').modal('show'); return false;" target="_blank">
                    Privacy Policy
                </a>
            </div>
        </div>
    </div>
</footer>

<?php
    Modal::begin([
        'title' => 'Terms & Conditions',
        'size'=>Modal::SIZE_LARGE,
        'id'=>'terms_modal'
    ]);
        echo $this->render('terms');
    Modal::end();

    Modal::begin([
        'title' => 'Privacy Policy',
        'size'=>Modal::SIZE_LARGE,
        'id'=>'privacy_modal'
    ]);
        echo $this->render('privacy');
    Modal::end();

?>
