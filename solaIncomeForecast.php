<?php
/* * ********************************************************************
 * Custom Report by WHMCS Services
 *
 * Created By WHMCSServices      http://www.whmcsservices.com
 * Contact Jose:	      		 dev@whmcsservices.com
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 * ******************************************************************** */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\solaIncomeForecast\Admin\AdminDispatcher;

function solaIncomeForecast_config() {
    return array(
        'name' => 'Income Forecast',
        'description' => 'Advanced analytical income reports',
        'author' => 'SolaDrive',
        'language' => 'english',
        'version' => '2.3',
        'fields' => array()
    );
}
function solaIncomeForecast_activate() {
    try {
        if (!Capsule::schema()->hasTable('mod_sola_incomeforecast')) {
            Capsule::schema()->create('mod_sola_incomeforecast', function ($table) {
                /** @var \Illuminate\Database\Schema\Blueprint $table */
                $table->increments('id');
                $table->timestamp('date')->nullable();
                $table->text('type');
                $table->text('timestamp');
                $table->text('month');
                $table->text('day');
                $table->decimal('annualtotal', 16, 2);
                $table->decimal('activetotal', 16, 2);
                $table->decimal('suspendedtotal', 16, 2);
                $table->decimal('addontotal', 16, 2);
                $table->decimal('suspendedaddontotal', 16, 2);

                $table->decimal('servicemonthly', 16, 2);
                $table->decimal('servicesemi', 16, 2);
                $table->decimal('servicebi', 16, 2);
                $table->decimal('servicequarterly', 16, 2);
                $table->decimal('serviceannually', 16, 2);
                $table->decimal('servicetriennially', 16, 2);
                $table->decimal('addonmonthly', 16, 2);
                $table->decimal('addonsemi', 16, 2);
                $table->decimal('addonbi', 16, 2);
                $table->decimal('addonquarterly', 16, 2);
                $table->decimal('addonannually', 16, 2);
                $table->decimal('addontriennially', 16, 2);
                $table->decimal('suspendedservicemonthly', 16, 2);
                $table->decimal('suspendedservicesemi', 16, 2);
                $table->decimal('suspendedservicebi', 16, 2);
                $table->decimal('suspendedservicequarterly', 16, 2);
                $table->decimal('suspendedserviceannually', 16, 2);
                $table->decimal('suspendedservicetriennially', 16, 2);
                $table->decimal('suspendedaddonmonthly', 16, 2);
                $table->decimal('suspendedaddonsemi', 16, 2);
                $table->decimal('suspendedaddonbi', 16, 2);
                $table->decimal('suspendedaddonquarterly', 16, 2);
                $table->decimal('suspendedaddonannually', 16, 2);
                $table->decimal('suspendedaddontriennially', 16, 2);

                $table->string('servicemonthlycount');
                $table->string('servicesemicount');
                $table->string('servicebicount');
                $table->string('servicequarterlycount');
                $table->string('serviceannuallycount');
                $table->string('servicetrienniallycount');
                $table->string('addonmonthlycount');
                $table->string('addonsemicount');
                $table->string('addonbicount');
                $table->string('addonquarterlycount');
                $table->string('addonannuallycount');
                $table->string('addontrienniallycount');
                $table->string('suspendedservicemonthlycount');
                $table->string('suspendedservicesemicount');
                $table->string('suspendedservicebicount');
                $table->string('suspendedservicequarterlycount');
                $table->string('suspendedserviceannuallycount');
                $table->string('suspendedservicetrienniallycount');
                $table->string('suspendedaddonmonthlycount');
                $table->string('suspendedaddonsemicount');
                $table->string('suspendedaddonbicount');
                $table->string('suspendedaddonquarterlycount');
                $table->string('suspendedaddonannuallycount');
                $table->string('suspendedaddontrienniallycount');


            });
        }
        return [
        // Supported values here include: success, error or info
        'status' => 'success', 'description' => 'The module was activated', ];
    }
    catch(\Exception $e) {
        return [
        // Supported values here include: success, error or info
        'status' => "error", 'description' => 'Unable to create table: ' . $e->getMessage() , ];
    }
}
function solaIncomeForecast_deactivate() {
  try {

    //   Capsule::schema()->dropIfExists('mod_sola_incomeforecast');

      return [
      // Supported values here include: success, error or info
      'status' => 'success', 'description' => 'The module was activated', ];
  }
  catch(\Exception $e) {
      return [
      // Supported values here include: success, error or info
      'status' => "error", 'description' => 'Unable to create table: ' . $e->getMessage() , ];
  }
}
function solaIncomeForecast_output($vars) {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action, $vars);
    
    echo $response;
}