<?php
include("functions.php");

if(isset( $_SESSION["ip"])){
    redirect_to("home.php");
}
?>


<html>
<head>
    <title>Monitoring System</title>
    <!--<link rel="stylesheet" type="text/css" href="Assets/css/bootstrap.css" />-->
    <style>
        * {
            padding: 5px;
        }

        .error {
            background-color: lightpink;
            border: 1px solid red;
            color: red;
        }
    </style>

</head>
<body>
    <div>
        <?php
        if(isset($_POST["submit"])){
            $output = "";
            exec("java -Xmx1g -jar executables/TCPCLient.jar {$_POST["ipaddress"]} CHK", $result);
            foreach ($result as $value){
                $output .= $value;
            }
            if($output === "ACK"){
                $_SESSION["ip"] = $_POST["ipaddress"];
                redirect_to("home.php");
            }else{
                echo "<div class=\"error\">";
                echo "Cannot connect. (" . $output. ")";
                echo "</div>";
            }

        }
        ?>
        <div>
            <h1>Monitoring System</h1>
            <form method="post" action="index.php">
                <table>
                    <tr>
                        <td>IP address</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="ipaddress" placeholder="IP Address" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="submit" name="submit" value="Start Monitoring" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
