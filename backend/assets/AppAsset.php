<?php

namespace backend\assets;

use common\models\Config;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/web/quantum';
    public $css = [
        //  <!-- ================== GOOGLE FONTS ==================-->
//        "https://fonts.googleapis.com/css?family=Poppins:300,400,500",
        "https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i",
        //  <!-- ======================= GLOBAL VENDOR STYLES ========================-->
        "assets/css/vendor/bootstrap.css",
        "assets/vendor/metismenu/dist/metisMenu.css",
        "assets/vendor/switchery-npm/index.css",
        "assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css",
        //  <!-- ======================= LINE AWESOME ICONS ===========================-->
        "assets/css/icons/line-awesome.min.css",
        "assets/css/icons/simple-line-icons.css",
//        "assets/css/icons/font-awesome.min.css",
        //  <!-- ======================= DRIP ICONS ===================================-->
        "assets/css/icons/dripicons.min.css",
        //  <!-- ======================= MATERIAL DESIGN ICONIC FONTS =================-->
        "assets/css/icons/material-design-iconic-font.min.css",
        //  <!-- ======================= PAGE VENDOR STYLES ===========================-->
        "assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css",
        //  <!-- ======================= GLOBAL COMMON STYLES ============================-->
        'assets/vendor/select2/select2.min.css',
        "assets/css/common/main.bundle.css",
        //  <!-- ======================= LAYOUT TYPE ===========================-->
        "assets/css/layouts/vertical/core/main.css",
        //  <!-- ======================= MENU TYPE ===========================-->
        "assets/css/layouts/vertical/menu-type/content.css",
        //  <!-- ======================= THEME COLOR STYLES ===========================-->
        "flags/css/flag-icon.css",
        //  <!-- ================== Custom styles ==================-->
        'dev.css',
    ];
    public $js = [
        //  <!-- ================== GLOBAL VENDOR SCRIPTS ==================-->
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.1.0/jquery-migrate.min.js',
        'assets/vendor/modernizr/modernizr.custom.js',
//        'assets/vendor/jquery/dist/jquery.min.js',
        'assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js',
        'assets/vendor/js-storage/js.storage.js',
        'assets/vendor/js-cookie/src/js.cookie.js',
        'assets/vendor/pace/pace.js',
        'assets/vendor/metismenu/dist/metisMenu.js',
        'assets/vendor/switchery-npm/index.js',
        'assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
        //  <!-- ================== PAGE LEVEL VENDOR SCRIPTS ==================-->
        'assets/vendor/countup.js/dist/countUp.min.js',
        'assets/vendor/chart.js/dist/Chart.bundle.min.js',
        'assets/vendor/flot/jquery.flot.js',
        'assets/vendor/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js',
        'assets/vendor/flot/jquery.flot.resize.js',
        'assets/vendor/flot/jquery.flot.time.js',
        'assets/vendor/flot.curvedlines/curvedLines.js',
        'assets/vendor/datatables.net/js/jquery.dataTables.js',
        'assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js',

        'assets/vendor/jvectormap-next/jquery-jvectormap.min.js',
        'assets/vendor/jvectormap-next/jquery-jvectormap-world-mill.js',
        'assets/vendor/chartist/dist/chartist.js',

        'assets/vendor/moment/min/moment.min.js',
        'assets/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js',
        'assets/vendor/bootstrap-daterangepicker/daterangepicker.js',
        'assets/vendor/sweetalert2/dist/sweetalert2.min.js',

        'assets/js/components/bootstrap-datepicker-init.js',
        'assets/vendor/select2/select2.min.js',
        'scripts/confirmation.js',

        //  <!-- ================== GLOBAL APP SCRIPTS ==================-->
        'assets/js/chr-crs.min.js',
        'assets/js/global/app.js',
        //  <!-- ================== PAGE LEVEL SCRIPTS ==================-->
        'assets/js/components/countUp-init.js',
        'assets/js/cards/counter-group.js',
        'assets/js/cards/recent-transactions.js',
        'assets/js/cards/users-chart.js',
        'assets/js/cards/bounce-rate-chart.js',
        'assets/js/cards/session-duration-chart.js',

        'assets/js/cards/total-visits-chart.js',
        'assets/js/cards/total-unique-visits-chart.js',

        'https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public static function register($view) {
        $bundle=parent::register($view);
        $js = "

        ";
        $bundle->css[]="assets/css/layouts/vertical/themes/theme-".Config::getParameter("theme", false).".css";
        $view->registerJs($js, \yii\web\View::POS_END);

        return $bundle;
    }
}
