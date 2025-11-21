<?php
include("../functions.php");



if(!isset($_SESSION["ip"])){
    redirect_to("../home.php");
    //$_SESSION["ip"] = "127.0.0.1";
}

$output = "";
exec("java -Xmx1g -jar ../executables/TCPClient.jar {$_SESSION["ip"]} CPUINFO", $result);

foreach ($result as $value){
    $output .= $value;
}

$cpu = json_decode($output);

?>

<html>
<head>
    <title>CPU
    </title>
    <link rel="stylesheet" type="text/css" href="../Assets/css/bootstrap.css" />
    <style>
        * {
            padding: 5px;
        }
    </style>
</head>
<body>
    <h2>CPU Info</h2>
    <div>

        <table class="table table-condensed" style="width: 60%">
            <tr>
                <td>Vendor</td>
                <td>:</td>
                <td>
                    <?php 
                    echo $cpu->vendor;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Model</td>
                <td>:</td>
                <td>
                    <?php 
                    echo $cpu->model;
                    ?>
                </td>
            </tr>

            <!--result += "\"cache\" : " + "\"" + cacheSize + "\",";-->

            <?php
            if(isset($cpu->cache)){
                echo "<tr>";
                echo "<td>Cache Size</td>";
                echo "<td>:</td>";
                echo "<td>" . $cpu->cache . "</td></tr>";
            }
            ?>
            <tr>
                <td>Total cores</td>
                <td>:</td>
                <td id="core">
                    <?php 
                    echo $cpu->cores;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Physical CPUs</td>
                <td>:</td>
                <td>
                    <?php 
                    echo $cpu->physicalCPUs;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Cores per CPU</td>
                <td>:</td>
                <td>
                    <?php 
                    echo $cpu->coreCPU;
                    ?>
                </td>
            </tr>

        </table>
    </div>


    <?php   
    exec("java -Xmx1g -jar ../executables/TCPClient.jar {$_SESSION["ip"]} PRINTCORES", $result1);
    $output1 = "";
    foreach ($result1 as $value){
        $output1 .= $value;
    }
    $var = json_decode($output1);


    for ($i = 0, $j=0; $i < count($var); $i++)
    {
    	if($i % 2 == 0){
            echo "<div class=\"row\">";
            echo "<div class=\"col-md-6\">";
            echo "<div class=\"row\" data-core=\"".$var[$j]->core."\" id=\"chart".$var[$j]->core."\"></div>";
            echo "<div class=\"row\">";
            echo "<table class=\"table\"><thead><tr><th>Core</th><th>User Time</th><th>System Time</th><th>Idle Time</th><th>Wait time</th><th>Nice time</th><th>Irq time</th></tr></thead>";
            echo "<tbody><tr><td id=\"core".$j."\">".$var[$j]->core."</td><td id=\"user".$j."\">".$var[$j]->userTime."</td><td id=\"sys".$j."\">".$var[$j]->sysTime."</td><td id=\"idle".$j."\">".$var[$j]->idleTime."</td><td id=\"wait".$j."\">".$var[$j]->waitTime."</td><td id=\"nice".$j."\">".$var[$j]->niceTime."</td><td id=\"irq".$j."\">".$var[$j]->irqTime."</td></tr></tbody></table>";
            echo "</div></div>";
            $j++;
            echo "<div class=\"row\">";
            echo "<div class=\"col-md-6\">";
            echo "<div class=\"row\" data-core=\"".$var[$j]->core."\" id=\"chart".$var[$j]->core."\"></div>";
            echo "<div class=\"row\">";
            echo "<table class=\"table\"><thead><tr><th>Core</th><th>User Time</th><th>System Time</th><th>Idle Time</th><th>Wait time</th><th>Nice time</th><th>Irq time</th></tr></thead>";
            echo "<tbody><tr><td id=\"core".$j."\">".$var[$j]->core."</td><td id=\"user".$j."\">".$var[$j]->userTime."</td><td id=\"sys".$j."\">".$var[$j]->sysTime."</td><td id=\"idle".$j."\">".$var[$j]->idleTime."</td><td id=\"wait".$j."\">".$var[$j]->waitTime."</td><td id=\"nice".$j."\">".$var[$j]->niceTime."</td><td id=\"irq".$j."\">".$var[$j]->irqTime."</td></tr></tbody></table>";
            echo "</div></div>";
            $j++;
            echo "</div>";
        }
    }
    /*
     * 
     * */
    
    ?>

    <script src="../Assets/js/jquery-2.1.4.js"></script>
    <script src="../Assets/js/canvasjs.min.js"></script>
    <script>
        var core;
        var charts = [];
        var updateInterval = 2000;
        var dpss = [];

        var xVal = 0;
        var dataLength = 70;

        function truncate(val) {
            var res = "";
            for (var i = 0; i < val.length - 1; i++) {
                res += val[i];
            }
            return res;
        }

        function update() {
            var newData;
            $.ajax({
                type: "GET",
                url: "ajax/update.php",
                async: true,
                success: function (response) {
                    newData = response;
                    for (var i = 0; i < core; i++) {
                        dpss[i].push({
                            x: xVal,
                            y: parseFloat(truncate(response[i].combined))
                        });

                        if (dpss[i].length > 100) {
                            dpss[i].shift();
                        }

                        charts[i].render();

                        $("#user" + i).text(response[i].userTime);
                        $("#sys" + i).text(response[i].sysTime);
                        $("#idle" + i).text(response[i].idleTime);
                        $("#wait" + i).text(response[i].waitTime);
                        $("#nice" + i).text(response[i].niceTime);
                        $("#irq" + i).text(response[i].irqTime);
                        xVal += 2;
                    }
                },
                complete: function () {
                    setTimeout(update(), 700);
                }

            });
        }

        // number of dataPoints visible at any point
        $(document).ready(function () {
            core = parseInt($("#core").text());


            for (var i = 0; i < core; i++) {
                $("div[data-core='" + i + "']").css("height", "300px");
                dpss[i] = [];
                charts.push(new CanvasJS.Chart("chart" + i, {
                    title: {
                        text: "CPU" + i
                    },
                    data: [{
                        type: "line",
                        dataPoints: dpss[i]
                    }]
                }));
            }

            $.ajax({
                type: "GET",
                url: "ajax/cpu.php?start=1",
                async: true
            });

            update();

        });

        $(window).bind('beforeunload', function () {
            $.ajax({
                type: "GET",
                url: "ajax/cpu.php?stop=1",
                async: true
            });

        });

    </script>


    <!--<div id="chartContainer" style="height: 300px; width: 100%;">
    </div>
    <script>
        window.onload = function () {

            var dps = []; // dataPoints

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "CPU usage"
                },
                data: [{
                    type: "line",
                    dataPoints: dps
                }]
            });

            var xVal = 0;
            var yVal = 100;
            var updateInterval = 1000;
            var dataLength = 70; // number of dataPoints visible at any point

            var updateChart = function (count) {
                count = count || 1;

                //update here
                dps.push({
                    x: 1,
                    y: 2
                });


                if (dps.length > dataLength) {
                    dps.shift();
                }

                chart.render();

            };

            // generates first set of dataPoints
            updateChart(dataLength);

            // update chart after specified time. 
            setInterval(function () { updateChart() }, updateInterval);

        }
    </script>
    <script src="../Assets/js/canvasjs.min.js"></script>-->
</body>
</html>

