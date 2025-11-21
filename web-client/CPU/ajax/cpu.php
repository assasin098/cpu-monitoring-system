<?php

session_start();

if(isset($_GET["start"])){
    exec("java -Xmx1g -jar ../../executables/TCPCLient.jar {$_SESSION["ip"]} PRINTCORE 127.0.0.1", $result1);
}else if(isset($_GET["stop"])){
    exec("java -Xmx1g -jar ../../executables/TCPCLient.jar {$_SESSION["ip"]} STOP", $result1);
}


?>