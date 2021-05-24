<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Вышеупомянутая ошибка произошла во время обработки вашего запроса веб-сервером.
<!--        The above error occurred while the Web server was processing your request.-->
    </p>
    <p>
        Свяжитесь с нами (pavel.chernetsky@gmail.com, tel: +38 093 891 24 35), если вы считаете, что это ошибка сервера. Спасибо.
<!--        Please contact us if you think this is a server error. Thank you.-->
    </p>

</div>
