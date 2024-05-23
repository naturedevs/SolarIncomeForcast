<?php
use WHMCS\Database\Capsule;
require_once ("Forecast.php");
use WHMCS\Module\Addon\AddonModule\Forecast;

add_hook('DailyCronJob', 1, function (array $params) {
	try {
		$forecast = new Forecast();
		$forecast->saveHistory("daily");
		//$forecast->saveHistory("monthly");
	} catch(Exception $e) {
		logActivity("CRON ERROR: Income Forest -> {$e->getMessage()}", 1);
	}
});
