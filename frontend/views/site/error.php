<?php
    /* @var $cart array */

    /* @var $this yii\web\View */
    /* @var $name string */
    /* @var $message string */
    /* @var $exception Exception */

    use yii\helpers\Html;

    $this->title = $name;

    $bundle=\frontend\assets\AppAsset::register($this);
?>

<section class="dashboard">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight_2"><?= Html::encode($this->title) ?></div>
                </div>
            </div>
            <div class="col-12">
                <p><?= nl2br(Html::encode($message)) ?></p>
            </div>
        </div>
    </div>
</section>

