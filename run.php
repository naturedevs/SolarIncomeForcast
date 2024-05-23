<?php
use WHMCS\Database\Capsule;
require_once ("Forecast.php");
require_once (__DIR__."/../../../configuration.php");
require_once (__DIR__."/../../../init.php");
require_once (__DIR__."/../../../vendor/autoload.php");
// require_once (__DIR__."/../../../includes/index.php");
// require_once (__DIR__."/../../../sola88/addonmodules.php");
use WHMCS\Module\Addon\AddonModule\Forecast;
echo "start\n";
// use WHMCS\Database\Capsule;
try {
    $forecast = new Forecast();
    $period = "Monthly";
    $status = "Active";
    $do = Capsule::table('tblhosting')->where('nextduedate', '<' , "2015-04-01")->get();
    // $forecast->saveHistory("daily");
    // $forcast->getHostedServices('Monthly', "Active");
    var_dump(count($do));
    echo "done\n";
    //$forecast->saveHistory("monthly");
} catch(Exception $e) {
    logActivity("CRON ERROR: Income Forest -> {$e->getMessage()}", 1);
    echo "error";
}
