<?php
use WHMCS\Database\Capsule;
require_once ("Forecast.php");
require_once (__DIR__."/../../../sola88/addonmodules.php");
use WHMCS\Module\Addon\AddonModule\Forecast;
try {
    $forecast = new Forecast();
    $forecast->saveHistory("daily");
    echo "done";
    //$forecast->saveHistory("monthly");
} catch(Exception $e) {
    logActivity("CRON ERROR: Income Forest -> {$e->getMessage()}", 1);
    echo "error";
}
