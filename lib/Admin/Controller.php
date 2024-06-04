<?php
namespace WHMCS\Module\Addon\solaIncomeForecast\Admin;
use WHMCS\Database\Capsule;
class Controller
{
    function __construct()
    {
        $current_date = date('Y-m-d');
        $this->date = mktime(0, 0, 0, 1, 27, 2014);
        $this->dayNumber = date("d", $date);
        $this->dayOfWeek = date("l", $date);
        $this->dayPosition = floor(($dayNumber - 1) / 7) + 1;
        $this->history = $this->getHistory();
        $this->historyMonthly = $this->getHistory("monthly");

        $this->monthlyServices = $this->getHostedServices1("Monthly", $current_date);
        $this->anualServices = $this->getHostedServices1("Annually", $current_date);
        $this->triServices = $this->getHostedServices1("Triennially", $current_date);
        $this->semiServices = $this->getHostedServices1("Semi-Annually", $current_date);
        $this->quarterServices = $this->getHostedServices1("Quarterly",$current_date);
        $this->biServices = $this->getHostedServices1("Biennially", $current_date);

        $this->monthlyAddons = $this->getHostedAddons1("Monthly", $current_date);
        $this->anualAddons = $this->getHostedAddons1("Annually", $current_date);
        $this->triAddons = $this->getHostedAddons1("Triennially", $current_date);
        $this->semiAddons = $this->getHostedAddons1("Semi-Annually", $current_date);
        $this->quarterAddons = $this->getHostedAddons1("Quarterly", $current_date);
        $this->biAddons = $this->getHostedAddons1("Biennially", $current_date);

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

        $this->suspendedMonthlyAddonsCount = count($this->suspendedMonthlyAddons);
		$this->suspendedAnnualAddonsCount = count($this->suspendedAnualAddons);
		$this->suspendedTriAddonsCount = count($this->suspendedTriAddons);
		$this->suspendedSemiAddonsCount = count($this->suspendedSemiServices);
		$this->suspendedQuarterAddonsCount = count($this->suspendedQuarterAddons);
		$this->suspendedBiAddonsCount = count($this->suspendedBiAddons);

		$this->suspendedMonthlyServicesCount = count($this->suspendedMonthlyServices);
		$this->suspendedAnnualServicesCount = count($this->suspendedAnualServices);
		$this->suspendedTriServicesCount = count($this->suspendedTriServices);
		$this->suspendedSemiServicesCount = count($this->suspendedSemiServices);
		$this->suspendedQuarterServicesCount = count($this->suspendedQuarterServices);
		$this->suspendedBiServicesCount = count($this->suspendedBiServices);

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
        $this->addonmonthlyAmount = 0.0;
        $this->addonannualAmount = 0.0;
        $this->addonbiannualAmount = 0.0;
        $this->addonsemiAmount = 0.0;
        $this->addontriennialAmount = 0.0;
        $this->addonquarterlyAmount = 0.0;
        $this->addonsuspendedMonthlyAmount = 0.0;
        $this->addonsuspendedAnnualAmount = 0.0;
        $this->addonsuspendedBiannualAmount = 0.0;
        $this->addonsuspendedSemiAmount = 0.0;
        $this->addonsuspendedTriennialAmount = 0.0;
        $this->addonsuspendedQuarterlyAmount = 0.0;
        $this->debugData = "";
        foreach ($this->monthlyServices as $service) {
            $this->monthlyAmount = $this->monthlyAmount + $service->amount * 12;
            $debugstep = $service->amount * 12;
            $this->debugData .= "<br>+{$debugstep}, now at {$this->monthlyAmount} USD with payment for user: {$service->userid} service: {$service->id}";
        }
        foreach ($this->anualServices as $service) {
            $this->annualAmount = $this->annualAmount + $service->amount;
        }
        foreach ($this->semiServices as $service) {
            $this->semiAmount = $this->semiAmount + $service->amount * 2;
        }
        foreach ($this->biServices as $service) {
            $this->biannualAmount = $this->biannualAmount + $service->amount / 2;
        }
        foreach ($this->triServices as $service) {
            $this->triennialAmount = $this->triennialAmount + $service->amount / 3;
        }
        foreach ($this->quarterServices as $service) {
            $this->quarterlyAmount = $this->quarterlyAmount + $service->amount * 4;
        }
        foreach ($this->monthlyAddons as $addon) {
            $this->addonmonthlyAmount = $this->addonmonthlyAmount + $addon->recurring * 12;
        }
        foreach ($this->anualAddons as $addon) {
            $this->addonannualAmount = $this->addonannualAmount + $addon->recurring;
        }
        foreach ($this->biAddons as $addon) {
            $this->addonbiannualAmount = $this->addonbiannualAmount + $addon->recurring / 2;
        }
        foreach ($this->semiAddons as $addon) {
            $this->addonsemiAmount = $this->addonsemiAmount + $addon->recurring * 2;
        }
        foreach ($this->triAddons as $addon) {
            $this->addontriennialAmount = $this->addontriennialAmount + $addon->recurring / 3;
        }
        foreach ($this->quarterAddons as $addon) {
            $this->addonquarterlyAmount = $this->addonquarterlyAmount + $addon->recurring * 4;
        }
        foreach ($this->suspendedMonthlyServices as $service) {
            $this->suspendedMonthlyAmount = $this->suspendedMonthlyAmount + $service->amount * 12;
        }
        foreach ($this->suspendedAnualServices as $service) {
            $this->suspendedAnnualAmount = $this->suspendedAnnualAmount + $service->amount;
        }
        foreach ($this->suspendedBiServices as $service) {
            $this->suspendedBiannualAmount = $this->suspendedBiannualAmount + $service->amount / 2;
        }
        foreach ($this->suspendedSemiServices as $service) {
            $this->suspendedSemiAmount = $this->suspendedSemiAmount + $service->amount * 2;
        }
        foreach ($this->suspendedTriServices as $service) {
            $this->suspendedTriennialAmount = $this->suspendedTriennialAmount + $service->amount / 3;
        }
        foreach ($this->suspendedQuarterServices as $service) {
            $this->suspendedQuarterlyAmount = $this->suspendedQuarterlyAmount + $service->amount * 4;
        }
        foreach ($this->suspendedMonthlyAddons as $service) {
            $this->addonsuspendedMonthlyAmount = $this->addonsuspendedMonthlyAmount + $service->recurring * 12;
        }
        foreach ($this->suspendedAnualAddons as $service) {
            $this->addonsuspendedAnnualAmount = $this->addonsuspendedAnnualAmount + $service->recurring;
        }
        foreach ($this->suspendedSemiAddons as $service) {
            $this->addonsuspendedSemiAmount = $this->addonsuspendedSemiAmount + $service->recurring * 2;
        }
        foreach ($this->suspendedBiAddons as $service) {
            $this->addonsuspendedBiannualAmount = $this->addonsuspendedBiannualAmount + $service->recurring / 2;
        }
        foreach ($this->suspendedTriAddons as $service) {
            $this->addonsuspendedTriennialAmount = $this->addonsuspendedTriennialAmount + $service->recurring / 3;
        }
        foreach ($this->suspendedQuarterAddons as $service) {
            $this->addonsuspendedQuarterlyAmount = $this->addonsuspendedQuarterlyAmount + $service->recurring * 4;
        }
        
        $this->suspendedTotal = $this->suspendedMonthlyAmount + $this->suspendedAnnualAmount + $this->suspendedBiannualAmount + $this->suspendedTriennialAmount + $this->suspendedQuarterlyAmount + $this->suspendedSemiAmount;
        $this->suspendedAddonTotal = $this->addonsuspendedMonthlyAmount + $this->addonsuspendedAnnualAmount + $this->addonsuspendedBiannualAmount + $this->addonsuspendedTriennialAmount + $this->addonsuspendedQuarterlyAmount + $this->addonsuspendedSemiAmount;
        $this->activeTotal = $this->monthlyAmount + $this->annualAmount + $this->biannualAmount + $this->triennialAmount + $this->quarterlyAmount + $this->semiAmount;
        $this->activeAddonTotal = $this->addonmonthlyAmount + $this->addonannualAmount + $this->addonbiannualAmount + $this->addontriennialAmount + $this->addonquarterlyAmount + $this->addonsemiAmount;
        $this->activeMonthlyTotal = $this->monthlyAmount;
        $this->suspendedMonthlyTotal = $this->suspendedMonthlyAmount;
        $this->monthlyTotalTotal = $this->suspendedMonthlyTotal + $this->activeMonthlyTotal;
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
    }

    public function getHostedServices($period, $status)
    {
        $do = Capsule::table("tblhosting")
            ->where("billingcycle", $period)
            ->where("domainstatus", $status)
            ->get();
        return $do;
    }
    public function getHostedAddons($period, $status)
    {
        $do = Capsule::table("tblhostingaddons")
            ->where("billingcycle", $period)
            ->where("status", "=", $status)
            ->get();
        return $do;
    }
    public function getHistory($type = "daily")
    {
        if ($type == "monthly") {
            /*$do = Capsule::table('mod_sola_incomeforecast')->select('month')->groupBy('month')->get();
        foreach ($do as $month)
        {
          $do = Capsule::table('mod_sola_incomeforecast')->where('month', '=', $month->month)->get();
          print_r($do);
        }
        die();
        return $do;*/
        }
        $do = Capsule::table("mod_sola_incomeforecast")->get();
        return $do;
    }

    protected $title = "Annual Income Archive";
    protected $description = "";
    protected $weight = 150;
    protected $columns = 3;
    protected $cache = false;
    //protected $cacheExpiry = 120;
    protected $requiredPermission = "";

    function getIncomeForecast($month, $year)
    {
        $incomeStats = getAdminHomeStats("income");
        foreach ($incomeStats["income"] as $key => $value) {
            $incomeStats["income"][$key] = $value->toPrefixed();
        }
        return $incomeStats;
    }

    public function getData()
    {
        $incomeStats = getAdminHomeStats("income");
        foreach ($incomeStats["income"] as $key => $value) {
            $incomeStats["income"][$key] = $value->toPrefixed();
        }
        return $incomeStats;
    }

    public function monthly()
    {
        global $_ADMINLANG;
        $data = $this->getData();

        //return $data;
        $months = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ];

        $labels = "";
        $amount = "";

        $incomeThisMonth = ltrim($data["income"]["thismonth"], '$');

        $curr_month_idx = intval(date("m")) - 1;

        $curr_year = date("Y");

        $next_year = intval($curr_year) + 1;

        $old_value = 0;
        for ($i = $curr_month_idx; $i < 12; $i++) {
            $labels .= "'" . $months[$i] . " " . $curr_year . "',";
            $amount .= $old_value . ",";
            $old_value += intval($incomeThisMonth);
        }

        for ($i = 0; $i < 12; $i++) {
            $labels .= "'" . $months[$i] . " " . $next_year . "',";
            $amount .= $old_value . ",";
            $old_value += intval($incomeThisMonth);
        }

        $labels = "[" . rtrim($labels, ",") . "]";
        $amount = "[" . rtrim($amount, ",") . "]";

        $yearoption = "";
        for ($i = date("Y"); $i >= 2012; $i--) {
            $selectedyeartxt = "";
            if ($i == $viewyear) {
                $selectedyeartxt = 'selected="selected"';
            }
            $yearoption .=
                '<option value="' .
                $i .
                '" ' .
                $selectedyeartxt .
                ">" .
                $i .
                "</option>";
        }
        $monthoption = "";
        for ($j = 12; $j >= 1; $j--) {
            if ($j <= 9) {
                $topt = "0" . $j;
            } else {
                $topt = $j;
            }
            if ($viewyear == date("Y")) {
                if ($topt > date("m")) {
                    continue;
                }
            }
            $selectedmnthtxt = "";
            if ($topt == $viewmonth) {
                $selectedmnthtxt = 'selected="selected"';
            }
            $monthoption .=
                '<option value="' .
                $topt .
                '" ' .
                $selectedmnthtxt .
                ">" .
                ($month_name =
                    date("F", mktime(0, 0, 0, $topt, 10)) . "</option>");
        }
return <<<EOF
<div style="padding:20px;text-align: right;">
    <p> Choose Currency:<b>USD</b></p>
</div>
    <canvas id="myChart1"></canvas>

<script>

$(document).ready(function() {
var chartObject = null;
var windowResizeTimeoutId = null;

$('#viewIncome').click(function() {

    refreshWidget('IncomeForecastWidget', 'viewmonth=' + $('#viewMonth').val() + '&viewyear=' + $('#viewYear').val());
});

$(window).resize(function() {
    if (windowResizeTimeoutId) {
        clearTimeout(windowResizeTimeoutId);
        windowResizeTimeoutId = null;
    }

    windowResizeTimeoutId = setTimeout(function() {
        if (typeof chartObject === 'object') {
            chartObject.resize(false);
        }
    }, 250);
});

var ctx = $("#myChart1");
var chartObject = new Chart(ctx, {
type: 'line',
data: {
labels: {$labels},
datasets: [{
    label: 'Cumulative Income Forecast Total',
    data: {$amount},
    backgroundColor: "rgba(51,92,249,0.4)",
    borderColor: "rgba(51,92,249,1.0)"
}],

}
});
});
</script>
EOF;
    }

    public function index($vars)
    {
        $modulelink = $vars["modulelink"]; // eg. addonmodules.php?module=addonmodule
        $version = $vars["version"]; // eg. 1.0
        $LANG = $vars["_lang"]; // an array of the currently loaded language variables

        $content = [];
        $activeServices = "";
        $suspendedServices = "";
        $allServices = "";
        $allmonthlyServices = "";

        foreach ($this->getHistory() as $item) {
            if (isset($item->date)) {
                $monthlytotal =
                    $item->suspendedservicemonthly + $item->servicemonthly;
                $activeServices .=
                    '{
         t: new Date("' .
                    $item->date .
                    '"),
         y: ' .
                    $item->activetotal .
                    '
       },';

                $suspendedServices .=
                    '{
       t: new Date("' .
                    $item->date .
                    '"),
       y: ' .
                    $item->suspendedtotal .
                    '
     },';
                $allServices .=
                    '{
     t: new Date("' .
                    $item->date .
                    '"),
     y: ' .
                    $item->annualtotal .
                    '
            },';
                            $allmonthlyServices .=
                                '{
            t: new Date("' .
                                $item->date .
                                '"),
            y: ' .
                                $monthlytotal .
                                '
            },';
            }
        }
        $year = date('Y');
        // $date = $year . '-01-01';
        $date = date('Y-m-d', strtotime('last month'));
        $content = print_r($this->history, 1);
return <<<EOF

<script>
    function updateChart(timeUnit, startDate) {
        myLine.scales['x-axis-0'].options.time.min = startDate;
        myLine.scales['x-axis-0'].options.time.unit = timeUnit;
        var today = new Date();
        today.setFullYear(today.getFullYear() - 12);
        if(startDate == today.toISOString().split('T')[0]){
            myLine.scales['x-axis-0'].options.time.unit = "month";
        }
        myLine.config.options.elements.point.radius = 0;
        myLine.update();
    }

    function calculateStartDate(range) {
        var today = new Date();
        switch(range) {
            case '7':
                today.setDate(today.getDate() - 7);
                break;

            case '30':
                today.setMonth(today.getMonth() - 1);
                break;
            
            case '180':
                today.setMonth(today.getMonth() - 6);
                break;
            
            case '365':
                today.setFullYear(today.getFullYear() - 1);
                break;

            case '1095':
                today.setFullYear(today.getFullYear() - 3);
                break;

            case '0':
                today.setFullYear(today.getFullYear() - 12);
                break;
        }
        return today.toISOString().split('T')[0];
    }

    $(document).ready(function() {
        document.getElementById('interval-select').addEventListener('change', function() {
            var timeUnit = this.value;
            var range = document.getElementById('period-select').value;
            var startDate = calculateStartDate(range);
    
            updateChart(timeUnit, startDate);
        });
    
        document.getElementById('period-select').addEventListener('change', function() {
            var range = this.value;
            var timeUnit = document.getElementById('interval-select').value;
            var startDate = calculateStartDate(range);
    
            updateChart(timeUnit, startDate);
        });
    });
</script>
</head>
<body>
    <div class="controls">
        <label for="interval-select">Select Interval:</label>
        <select id="interval-select">
            <option value="day">Daily</option>
            <option value="week" selected>Weekly</option>
            <option value="month">Monthly</option>
        </select>

        <label for="period-select">Select Period:</label>
        <select id="period-select">
            <option value="7">Last 7 Days</option>
            <option value="30" selected>Last 30 Days</option>
            <option value="180">Last 6 Months</option>
            <option value="365">Last Year</option>
            <option value="1095">Last 3 Years</option>
            <option value="0">All Years</option>
        </select>
    </div>
    <canvas id="chart-01" height="500"  style="background-color:rgba(255,255,255,1.00);border-radius:0px;width:100%;height:500px;padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px"></canvas>
    <script>
        function MoreChartOptions(){}
            var ChartData = {
                datasets: [{
                    data: [{$activeServices}],
                    backgroundColor :'rgba(45,227,76,0)',
                    borderColor : 'rgba(0,162,255,0.5)',
                    pointBackgroundColor:'rgba(45,227,76,0)',
                    pointBorderColor : 'rgba(45,227,76,0)',
                    label:"Active Services"
                }, {
                    data: [{$allmonthlyServices}],
                    backgroundColor :'rgba(237,71,71,0)',
                    borderColor : '#f26464',
                    pointBackgroundColor:'rgba(237,71,71,0)',
                    pointBorderColor : 'rgba(237,71,71,0)',
                    label:"Monthly Services"
                }, {
                    data: [{$allServices}],
                    backgroundColor :'rgba(92,184,92,0)',
                    borderColor : '#5cb85c',
                    pointBackgroundColor:'rgba(237,71,71,0)',
                    pointBorderColor : 'rgba(237,71,71,0)',
                    label:"All Services"
                }]
            };

            ChartOptions = {
                responsive:true,
                scales: {
                    xAxes: [{
                        type: 'time',
                        display: true,
                        time: {
                            parser: "YYYY-MM-DD",
                            min: "$date",
                            unit: 'week'
                        }
                    }],

                    yAxes:[{
                        gridLines:{borderDash:[],},
                        ticks: {
                            stepSize: 50000
                        }
                    }],
                },
                plugins: {
                    datalabels:{
                        display:true,
                        font: {
                            style:' bold'
                        }
                    }
                },
                legend: {
                    labels: {
                        generateLabels: function(chart){
                            return chart.data.datasets.map(function(dataset, i) {
                                return {
                                    text:dataset.label,
                                    lineCap:dataset.borderCapStyle,
                                    lineDash:[],
                                    lineDashOffset: 0,
                                    lineJoin:dataset.borderJoinStyle,
                                    fillStyle:dataset.backgroundColor,
                                    strokeStyle:dataset.borderColor,
                                    lineWidth:dataset.pointBorderWidth,
                                    lineDash:dataset.borderDash,
                                }
                            })
                        },
                    },
                },
                title: {
                    display:true,
                    text:'Income Forecast',
                    fontColor:'#3498db',
                    fontSize:32,
                    fontStyle:' bold',
                },
                elements: {
                    arc: {},
                    point: {
                        radius:0,
                        borderWidth:0,
                    },
                    line: {
                        tension:0.4
                    },
                    rectangle: {},
                },
                tooltips: {},
                hover: {
                    mode:'nearest',
                    animationDuration:400,
                },
            };
            var ChartId = "chart-01";
            var ChartType = "line";
            var myLine = new Chart(document.getElementById(ChartId).getContext("2d"),{type:ChartType,data:ChartData,options:ChartOptions});
            document.getElementById(ChartId).getContext("2d").stroke();
    </script>
<p><br>
<!--h2>As of now:</h2>
If all {$this->totalServices} services and their {$this->totalAddons} addons are renewed for the year you will generate <b>{$this->annualtotal}</b><br>
If only the {$this->totalActiveServices} active services and their {$this->totalActiveAddons} addons are renewed for the year  you will generate <b>{$this->activeTotalTotal}</b><br>
If only the {$this->totalSuspendedServices} suspended services and their {$this->totalSuspendedAddons} addons are renewed for the year  you will generate <b>{$this->suspendedTotalTotal}</b><br>
If only the {$this->totalActiveServices} active services are renewed for the year  you will generate <b>{$this->activeTotal}</b><br>
If only the {$this->totalActiveAddons} active addons are renewed for the year  you will generate <b>{$this->activeAddonTotal}</b><br>
If only the {$this->totalSuspendedAddons} suspended addons are renewed for the year  you will generate <b>{$this->suspendedAddonTotal}</b>
</p--></b>
<p><br></br></p><p>
<h2>Services:</h2>
Monthly: {$this->monthlyServicesCount}  = {$this->monthlyAmount}<br>
Annually: {$this->annualServicesCount}  = {$this->annualAmount} <br>
Semi-Annual: {$this->semiServicesCount}  = {$this->biannualAmount}<br>
Quarterly Services: {$this->quarterServicesCount}  = {$this->quarterlyAmount} <br>
Triennially: {$this->triServicesCount}  = {$this->triennialAmount}<br>
Biennially Services: {$this->biServicesCount}  = {$this->biannualAmount}<br>
<br><b>Service Total: {$this->serviceTotal}</b>
<br>________________________</br>
<br></br><p></p>
<h2>Addons:</h2>
Monthly: {$this->monthlyAddonsCount}  = {$this->addonmonthlyAmount}<br>
Annually: {$this->annualAddonsCount}  = {$this->addonannualAmount} <br>
Semi-Annual: {$this->semiAddonsCount}  = {$this->addonbiannualAmount}<br>
Quarterly: {$this->quarterAddonsCount}  = {$this->addonquarterlyAmount} <br>
Triennially: {$this->triAddonsCount}  = {$this->addontriennialAmount}<br>
Biennially: {$this->triAddonsCount}  = {$this->addonbiannualAmount}<br>
<br><b>Addon Total: {$this->addonTotal}<b>
</p>
<p>Addons + Services = <b>{$this->annualtotal}</b>

EOF;
        // <p>
        // DEBUGGING MATH<p>

        // {$this->debugData}
    }

    public function show($vars)
    {
        // Get common module parameters
        $modulelink = $vars["modulelink"]; // eg. addonmodules.php?module=addonmodule
        $version = $vars["version"]; // eg. 1.0
        $LANG = $vars["_lang"]; // an array of the currently loaded language variables
        // Get module configuration parameters
        $configTextField = $vars["Text Field Name"];
        $configPasswordField = $vars["Password Field Name"];
        $configCheckboxField = $vars["Checkbox Field Name"];
        $configDropdownField = $vars["Dropdown Field Name"];
        $configRadioField = $vars["Radio Field Name"];
        $configTextareaField = $vars["Textarea Field Name"];

        return <<<EOF

            <h2>Show</h2>

            <p>This is the <em>show</em> action output of the sample addon module.</p>

            <p>The currently installed version is: <strong>{$version}</strong></p>

            <p>
                <a href="{$modulelink}" class="btn btn-info">
                    <i class="fa fa-arrow-left"></i>
                    Back to home
                </a>
            </p>

            EOF;
    }
    public function getHostedServices1($period, $date)
    {
        $do = Capsule::table('tblhosting')->where('billingcycle', $period)->where('nextduedate', '>' , $date)->where('regdate', '<' , $date)->get();
        return $do;
    }
    public function getHostedAddons1($period, $date)
    {
        $do = Capsule::table('tblhostingaddons')->where('billingcycle', $period)->where('nextduedate', '>' , $date)->where('regdate', '<' , $date)->get();
        return $do;
    }
}
