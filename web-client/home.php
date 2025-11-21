<?php
include("functions.php");

if(!isset($_SESSION["ip"])){
    redirect_to("index.php");
    //$_SESSION["ip"] = "127.0.0.1";
}

?>

<!DOCTYPE>
<html>
<head>
    <title>Welcome
    </title>
    <link rel="stylesheet" type="text/css" href="Assets/css/bootstrap.css" />
    <style>
        * {
            padding: 5px;
        }
    </style>

</head>
<body>
    <div>
        <?php
        $output = "";
        exec("java -Xmx1g -jar executables/TCPClient.jar {$_SESSION["ip"]} SYSINFO", $result);
        foreach ($result as $value){
            $output .= $value;
        }
        //$output = "{  \"temp\": " . $output . "}";
        
        $var = json_decode($output);

        ?>
        <h2>Connected to <?php echo $var->Native->fqdn . " (" . $_SESSION["ip"] . ")"; ?></h2>
        <div class="row">

            <div class=" col-md-5">
                <table class="table table-condensed">
                    <tr>
                        <td>PC name</td>
                        <td>:</td>
                        <td><?php echo $var->Native->fqdn ?></td>
                    </tr>
                    <tr>
                        <td>Current User
                        </td>
                        <td>:</td>
                        <td>
                            <?php 
                            echo $var->user
                            ?>
                        </td>
                    </tr>

                    <?php
                    if(isset($var->Native->language)){
                        echo "<tr>";
                        echo "<td>Language</td><td>:</td>";
                        echo "<td>" . $var->Native->language ."</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Language ID</td><td>:</td>";
                        echo "<td>" . $var->Native->langID ."</td>";
                        echo "</tr>";
                    }
                    
                    ?>

                    <tr>
                        <td>Operating System</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->name;
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td>OS Description</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->desc;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Architecture</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->arch;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Machine</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->machine;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Version</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->version;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Patch Level</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->patch;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Vendor</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->vendor;
                            ?>
                        </td>
                    </tr>

                    <?php
                    if(isset($var->codeName)){
                        echo "<tr>";
                        echo "<td>Vendor Code Name</td><td>:</td>";
                        echo "<td>" . $var->codeName ."</td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td>Data Model</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->dataModel;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>CPU Endian</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->cpuEndian;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Java Version</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->javaVersion;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Java Vendor</td>
                        <td>:</td>
                        <td>
                            <?php
                            echo $var->javaVendor;
                            ?>
                        </td>
                    </tr>

                </table>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <a href="CPU/" class="btn btn-default" role="button"><span class="glyphicon glyphicon-stats"></span>CPU</a>
                    <div class="text-info">
                        <span class="glyphicon glyphicon-info-sign"></span>See the statistic of the CPU, real time usage and detail information about CPU
                    </div>

                </div>
            </div>
        </div>
</body>
</html>
