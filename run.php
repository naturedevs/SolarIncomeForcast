<?php
use WHMCS\Database\Capsule;
require_once ("Forecast.php");
require_once (__DIR__."/../../../configuration.php");
require_once (__DIR__."/../../../init.php");
require_once (__DIR__."/../../../vendor/autoload.php");
// require_once (__DIR__."/../../../includes/index.php");
// require_once (__DIR__."/../../../sola88/addonmodules.php");
use WHMCS\Module\Addon\AddonModule\Forecast1;
echo "start\n";
// use WHMCS\Database\Capsule;
try {
    $forecast = new Forecast1();
    echo "done\n";
    //$forecast->saveHistory("monthly");
} catch(Exception $e) {
    logActivity("CRON ERROR: Income Forest -> {$e->getMessage()}", 1);
    echo "error";
}
