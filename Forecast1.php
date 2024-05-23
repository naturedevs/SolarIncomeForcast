<?php
namespace WHMCS\Module\Addon\AddonModule;
use WHMCS\Database\Capsule;
class Forecast1
{
    function __construct()
    {
        // $start = new date('2012-03-01');
        $start_date = '2021-03-01'; // Start date
        $current_date = date('Y-m-d'); // Current date
        for ($date = strtotime($start_date); $date <= strtotime($current_date); $date += 86400) {
            echo date('Y-m-d', $date). "\n"; // Print the date in YYYY-MM-DD format
            // Get today's date
            // $now = new date();

            // Iterate over dates from the start date to today
            // while ($start <= $now) {
            //     echo $start->format('Y-m-d'). "\n"; // Print the date in YYYY-MM-DD format
            //     $start->modify('+1 day'); // Increment the start date by one day
            // }
            $date1 = date('Y-m-d', $date);
            $this->dayNumber = date("d", $date);
            $this->dayOfWeek = date("l", $date);
            $this->dayPosition = (floor(($dayNumber - 1) / 7) + 1);

            $this->monthlyServices = $this->getHostedServices1('Monthly', $date1);
            $this->anualServices = $this->getHostedServices1('Annually', $date1);
            $this->triServices = $this->getHostedServices1('Triennially', $date1);
            $this->semiServices = $this->getHostedServices1('Semi-Annually', $date1);
            $this->quarterServices = $this->getHostedServices1('Quarterly', $date1);
            $this->biServices = $this->getHostedServices1('Biennially', $date1);

            $this->monthlyAddons = $this->getHostedAddons1('Monthly', $date1);
            $this->anualAddons = $this->getHostedAddons1('Annually', $date1);
            $this->triAddons = $this->getHostedAddons1('Triennially', $date1);
            $this->semiAddons = $this->getHostedAddons1('Semi-Annually', $date1);
            $this->quarterAddons = $this->getHostedAddons1('Quarterly', $date1);
            $this->biAddons = $this->getHostedAddons1('Biennially', $date1);

            $this->suspendedMonthlyServices = [];
            $this->suspendedAnualServices = [];
            $this->suspendedTriServices = [];
            $this->suspendedSemiServices = [];
            $this->suspendedQuarterServices = [];
            $this->suspendedBiServices = [];

            $this->suspendedMonthlyAddons = [];
            $this->suspendedAnualAddons = [];
            $this->suspendedTriAddons = [];
            $this->suspendedSemiAddons = [];
            $this->suspendedQuarterAddons = [];
            $this->suspendedBiAddons = [];

            $this->monthlyServicesCount = count($this->monthlyServices);
            $this->annualServicesCount = count($this->anualServices);
            $this->triServicesCount = count($this->triServices);
            $this->semiServicesCount = count($this->semiServices);
            $this->quarterServicesCount = count($this->quarterServices);
            $this->biServicesCount = count($this->biServices);

            $this->monthlyAddonsCount = count($this->monthlyAddons);
            $this->annualAddonsCount = count($this->anualAddons);
            $this->triAddonsCount = count($this->triAddons);
            $this->semiAddonsCount = count($this->semiAddons);
            $this->quarterAddonsCount = count($this->quarterAddons);
            $this->biAddonsCount = count($this->biAddons);

            $this->suspendedMonthlyAddonsCount = 0;
            $this->suspendedAnnualAddonsCount = 0;
            $this->suspendedTriAddonsCount = 0;
            $this->suspendedSemiAddonsCount = 0;
            $this->suspendedQuarterAddonsCount = 0;
            $this->suspendedBiAddonsCount = 0;

            $this->suspendedMonthlyServicesCount = 0;
            $this->suspendedAnnualServicesCount = 0;
            $this->suspendedTriServicesCount = 0;
            $this->suspendedSemiServicesCount = 0;
            $this->suspendedQuarterServicesCount = 0;
            $this->suspendedBiServicesCount = 0;

            $this->totalActiveServices = $this->monthlyServicesCount + $this->biServicesCount + $this->annualServicesCount + $this->triServicesCount + $this->semiServicesCount + $this->quarterServicesCount;
            $this->totalSuspendedServices = $this->suspendedMonthlyServicesCount + $this->suspendedBiServicesCount + $this->suspendedAnnualServicesCount + $this->suspendedTriServicesCount + $this->suspendedSemiServicesCount + $this->suspendedQuarterServicesCount;
            $this->totalActiveAddons = $this->monthlyAddonsCount + $this->biAddonsCount + $this->annualAddonsCount + $this->triAddonsCount + $this->semiAddonsCount + $this->quarterAddonsCount;
            $this->totalSuspendedAddons = $this->suspendedMonthlyAddonsCount + $this->suspendedBiAddonsCount + $this->suspendedAnnualAddonsCount + $this->suspendedTriAddonsCount + $this->suspendedSemiAddonsCount + $this->suspendedQuarterAddonsCount;

            $this->currentMonth = date("n");
            $this->monthsLeft = 12 - date("n");

            $this->monthlyAmount = 0.0;
            $this->annualAmount = 0.0;
            $this->biannualAmount = 0.0;
            $this->semiAmount = 0.0;
            $this->triennialAmount = 0.0;
            $this->quarterlyAmount = 0.0;
            $this->suspendedMonthlyAmount = 0.0;
            $this->suspendedAnnualAmount = 0.0;
            $this->suspendedBiannualAmount = 0.0;
            $this->suspendedSemiAmount = 0.0;
            $this->suspendedTriennialAmount = 0.0;
            $this->suspendedQuarterlyAmount = 0.0;
            $this->addonmonthlyAmount = 0.00;
            $this->addonannualAmount = 0.00;
            $this->addonbiannualAmount = 0.00;
            $this->addonsemiAmount = 0.00;
            $this->addontriennialAmount = 0.00;
            $this->addonquarterlyAmount = 0.00;
            $this->addonsuspendedMonthlyAmount = 0.00;
            $this->addonsuspendedAnnualAmount = 0.00;
            $this->addonsuspendedBiannualAmount = 0.00;
            $this->addonsuspendedSemiAmount = 0.00;
            $this->addonsuspendedTriennialAmount = 0.00;
            $this->addonsuspendedQuarterlyAmount = 0.00;
            $this->debugData = "";
            foreach ($this->monthlyServices as $service)
            {
                $this->monthlyAmount = $this->monthlyAmount + $service->amount * 12;
                $debugstep = $service->amount * 12;
                $this->debugData = $this->debugData . "<br>+{$debugstep}, now at {$this->monthlyAmount} USD with payment for user: {$service->userid} service: {$service->id}";
            }
            foreach ($this->anualServices as $service)
            {
                $this->annualAmount = $this->annualAmount + $service->amount;
            }
            foreach ($this->semiServices as $service)
            {
                $this->semiAmount = $this->semiAmount + $service->amount * 2;
            }
            foreach ($this->biServices as $service)
            {
                $this->biannualAmount = $this->biannualAmount + $service->amount / 2;
            }
            foreach ($this->triServices as $service)
            {
                $this->triennialAmount = $this->triennialAmount + $service->amount / 3;
            }
            foreach ($this->quarterServices as $service)
            {
                $this->quarterlyAmount = $this->quarterlyAmount + $service->amount * 4;
            }

            foreach ($this->monthlyAddons as $addon)
            {
                $this->addonmonthlyAmount = $this->addonmonthlyAmount + $addon->recurring * 12;
            }
            foreach ($this->anualAddons as $addon)
            {
                $this->addonannualAmount = $this->addonannualAmount + $addon->recurring;
            }
            foreach ($this->biAddons as $addon)
            {
                $this->addonbiannualAmount = $this->addonbiannualAmount + $addon->recurring / 2;
            }
            foreach ($this->semiAddons as $addon)
            {
                $this->addonsemiAmount = $this->addonsemiAmount + $addon->recurring * 2;
            }
            foreach ($this->triAddons as $addon)
            {
                $this->addontriennialAmount = $this->addontriennialAmount + $addon->recurring / 3;
            }
            foreach ($this->quarterAddons as $addon)
            {
                $this->addonquarterlyAmount = $this->addonquarterlyAmount + $addon->recurring * 4;
            }

            foreach ($this->suspendedMonthlyServices as $service)
            {
                $this->suspendedMonthlyAmount = $this->suspendedMonthlyAmount + $service->amount * 12;
            }
            foreach ($this->suspendedAnualServices as $service)
            {
                $this->suspendedAnnualAmount = $this->suspendedAnnualAmount + $service->amount;
            }
            foreach ($this->suspendedBiServices as $service)
            {
                $this->suspendedBiannualAmount = $this->suspendedBiannualAmount + $service->amount / 2;
            }
            foreach ($this->suspendedSemiServices as $service)
            {
                $this->suspendedSemiAmount = $this->suspendedSemiAmount + $service->amount * 2;
            }
            foreach ($this->suspendedTriServices as $service)
            {
                $this->suspendedTriennialAmount = $this->suspendedTriennialAmount + $service->amount / 3;
            }
            foreach ($this->suspendedQuarterServices as $service)
            {
                $this->suspendedQuarterlyAmount = $this->suspendedQuarterlyAmount + $service->amount * 4;
            }

            foreach ($this->suspendedMonthlyAddons as $service)
            {
                $this->addonsuspendedMonthlyAmount = $this->addonsuspendedMonthlyAmount + $service->recurring * 12;
            }
            foreach ($this->suspendedAnualAddons as $service)
            {
                $this->addonsuspendedAnnualAmount = $this->addonsuspendedAnnualAmount + $service->recurring;
            }
            foreach ($this->suspendedSemiAddons as $service)
            {
                $this->addonsuspendedSemiAmount = $this->addonsuspendedSemiAmount + $service->recurring * 2;
            }
            foreach ($this->suspendedBiAddons as $service)
            {
                $this->addonsuspendedBiannualAmount = $this->addonsuspendedBiannualAmount + $service->recurring / 2;
            }
            foreach ($this->suspendedTriAddons as $service)
            {
                $this->addonsuspendedTriennialAmount = $this->addonsuspendedTriennialAmount + $service->recurring / 3;
            }
            foreach ($this->suspendedQuarterAddons as $service)
            {
                $this->addonsuspendedQuarterlyAmount = $this->addonsuspendedQuarterlyAmount + $service->recurring * 4;
            }

            $this->suspendedTotal = $this->suspendedMonthlyAmount + $this->suspendedAnnualAmount + $this->suspendedBiannualAmount + $this->suspendedTriennialAmount + $this->suspendedQuarterlyAmount + $this->suspendedSemiAmount;
            $this->suspendedAddonTotal = $this->addonsuspendedMonthlyAmount + $this->addonsuspendedAnnualAmount + $this->saddonuspendedBiannualAmount + $this->addonsuspendedTriennialAmount + $this->addonsuspendedQuarterlyAmount + $this->addonsuspendedSemiAmount;
            $this->activeTotal = $this->monthlyAmount + $this->annualAmount + $this->biannualAmount + $this->triennialAmount + $this->quarterlyAmount + $this->semiAmount;
            $this->activeAddonTotal = $this->addonmonthlyAmount + $this->addonannualAmount + $this->addonbiannualAmount + $this->addontriennialAmount + $this->addonquarterlyAmount + $this->addonsemiAmount;

            $this->activeTotalTotal = $this->activeTotal + $this->activeAddonTotal;
            $this->suspendedTotalTotal = $this->suspendedTotal + $this->suspendedAddonTotal;
            $this->addonTotal = $this->suspendedAddonTotal + $this->activeAddonTotal;
            $this->serviceTotal = $this->activeTotal + $this->suspendedTotal;
            $this->annualtotal = $this->activeTotalTotal + $this->suspendedTotalTotal;

            $this->activeCount = $this->monthlyServicesCount + $this->annualServicesCount + $this->triServicesCount + $this->semiServicesCount + $this->quarterServicesCount;
            $this->suspendCount = $this->suspendedMonthlyServicesCount + $this->suspendedAnnualServicesCount + $this->suspendedTriServicesCount + $this->suspendedSemiServicesCount + $this->suspendedQuarterServicesCount;
            $this->activeAddonCount = $this->monthlyAddonsCount + $this->annualAddonsCount + $this->triAddonsCount + $this->semiAddonsCount + $this->quarterAddonsCount;
            $this->suspendedAddonCount = $this->suspendedMonthlyAddonsCount + $this->suspendedAnnualAddonsCount + $this->suspendedTriAddonsCount + $this->suspendedSemiAddonsCount + $this->suspendedQuarterAddonsCount;
            $this->totalServices = $this->activeCount + $this->suspendCount;
            $this->totalAddons = $this->activeAddonCount + $this->suspendedAddonCount;
            $this->totalSuspendedCount = $this->suspendCount + $this->suspendedAddonCount;
            $this->totalActiveCount = $this->activeCount + $this->activeAddonCount;
            $this->totalCount = $this->activeCount + $this->suspendCount + $this->activeAddonCount + $this->suspendedAddonCount;
            $this->saveHistory("daily", $date1);
        }

    }
    public function saveHistory($type = "daily", $date)
    {
        try
        {
            if ($type == "monthly")
            {

            }
            return Capsule::table('mod_sola_incomeforecast')->insert(array(
                'type' => $type,
                'timestamp' => strtotime($date) ,
                'month' => $this->currentMonth,
                'day' => $this->dayNumber,
                'date' => $date ,
                'annualtotal' => $this->annualtotal,
                'activetotal' => $this->activeTotal,
                'suspendedtotal' => $this->suspendedTotal,
                'addontotal' => $this->activeAddonTotal,
                'suspendedaddontotal' => $this->suspendedAddonTotal,

                'servicemonthly' => $this->monthlyAmount,
                'servicesemi' => $this->semiAmount,
                'servicebi' => $this->biannualAmount,
                'servicequarterly' => $this->quarterServices,
                'serviceannually' => $this->annualAmount,
                'servicetriennially' => $this->triennialAmount,

                'addonmonthly' => $this->monthlyAddons,
                'addonsemi' => $this->semiAddons,
                'addonbi' => $this->biAddons,
                'addonquarterly' => $this->quarterlyAmount,
                'addonannually' => $this->anualAddons,
                'addontriennially' => $this->triAddons,

                'suspendedservicemonthly' => $this->semiServices,
                'suspendedservicesemi' => $this->suspendedSemiServices,
                'suspendedservicebi' => $this->suspendedBiServices,
                'suspendedservicequarterly' => $this->suspendedQuarterServices,
                'suspendedserviceannually' => $this->quarterAddons,
                'suspendedservicetriennially' => $this->suspendedTriServices,

                'suspendedaddonmonthly' => $this->suspendedMonthlyAddons,
                'suspendedaddonsemi' => $this->semiServices,
                'suspendedaddonbi' => $this->biServices,
                'suspendedaddonquarterly' => $this->suspendedQuarterAddons,
                'suspendedaddonannually' => $this->suspendedAnualAddons,
                'suspendedaddontriennially' => $this->suspendedTriAddons,

                'servicemonthlycount' => $this->monthlyServicesCount,
                'servicesemicount' => $this->semiServicesCount,
                'servicebicount' => $this->biServicesCount,
                'servicequarterlycount' => $this->quarterServicesCount,
                'serviceannuallycount' => $this->annualServicesCount,
                'servicetrienniallycount' => $this->triServicesCount,

                'addonmonthlycount' => $this->monthlyAddonsCount,
                'addonsemicount' => $this->semiAddonsCount,
                'addonbicount' => $this->biAddonsCount,
                'addonquarterlycount' => $this->quarterAddonsCount,
                'addonannuallycount' => $this->annualAddonsCount,
                'addontrienniallycount' => $this->triAddonsCount,

                'suspendedservicemonthlycount' => $this->suspendedTriAddons,
                'suspendedservicesemicount' => $this->suspendedTriAddons,
                'suspendedservicebicount' => $this->suspendedBiAddons,
                'suspendedservicequarterlycount' => $this->suspendedTriAddons,
                'suspendedserviceannuallycount' => $this->suspendedTriAddons,
                'suspendedservicetrienniallycount' => $this->suspendedTriAddons,

                'suspendedaddonmonthlycount' => $this->suspendedMonthlyAddonsCount,
                'suspendedaddonsemicount' => $this->suspendedSemiAddonsCount,
                'suspendedaddonbicount' => $this->suspendedBiAddonsCount,
                'suspendedaddonquarterlycount' => $this->suspendedQuarterAddonsCount,
                'suspendedaddonannuallycount' => $this->suspendedAnnualAddonsCount,
                'suspendedaddontrienniallycount' => $this->suspendedTriAddons
            ));
        }
        catch(Exception $e)
        {
            logActivity("[ERROR] Income Forest -> {$e->getMessage() }", 1);
        }
    }
    public function getHostedServices($period, $status)
    {
        $do = Capsule::table('tblhosting')->where('billingcycle', $period)->where('domainstatus', '=', $status)->get();
        return $do;
    }
    public function getHostedAddons($period, $status)
    {
        $do = Capsule::table('tblhostingaddons')->where('billingcycle', $period)->where('status', '=', $status)->get();
        return $do;
    }
    public function getHostedServices1($period, $date)
    {
        $do = Capsule::table('tblhosting')->where('billingcycle', $period)->where('nextduedate', '<' , $date)->get();
        return $do;
    }
    public function getHostedAddons1($period, $date)
    {
        $do = Capsule::table('tblhostingaddons')->where('billingcycle', $period)->where('nextduedate', '<' , $date)->get();
        return $do;
    }
}

