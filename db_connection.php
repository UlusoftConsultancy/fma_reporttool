<?php

    $dbserver = "localhost";
    $dbuser = "root";
    $dbpassword = "";
    $dbname = "fma_rapportage";

    $mysqli = new mysqli($dbserver, $dbuser, $dbpassword, $dbname);

    // Check connection
    if ($mysqli->connect_errno) 
    {    
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

?>