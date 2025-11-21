<?php

session_start();


/**
 * Redirect to a web page
 * @param string $link the loaction path
 */
function redirect_to($link){
    header("location:{$link}");
}


/**
 * Debugging a variable
 * @param mixed $var 
 */
function debug($var){
    echo "<div style=\"width:auto; border:1px solid blue; padding:5px; margin:0px;\">";
    echo "debugging var";
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    echo "</div>";
}

?>