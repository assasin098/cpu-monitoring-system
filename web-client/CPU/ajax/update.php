<?php

session_start();

exec("java -Xmx1g -jar ../../executables/PacketReceiver.jar", $result1);

$output1 = "";
foreach ($result1 as $value){
    $output1 .= $value;
}

header("Content-Type: application/json");
echo $output1;


?>