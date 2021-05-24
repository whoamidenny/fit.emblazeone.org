<?php

namespace frontend\actions;


class GetProfileImageAction extends \yii\base\Action {

    public function run() {
        $result='
            <script>
                $.pjax.reload({container: "#profile"});
                $("#profile").on("pjax:success", function(event, data, status, xhr, options) {
                    $.pjax.reload({container: "#header"});
                });
            </script>
        ';

        return $result;
    }


}
