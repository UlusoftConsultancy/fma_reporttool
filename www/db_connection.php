<?php

    // $dbserver = "ID370587_apkdmu.db.webhosting.be";
    // $dbuser = "ID370587_apkdmu";
    // $dbpassword = "909L9TO55S2K3t3qn9b5";
    // $dbname = "ID370587_apkdmu";

    // $dbserver = "ulusoft.be.mysql";
    // $dbuser = "ulusoft_be_fma_prestaties";
    // $dbpassword = "GtuSE5EZX3HXsF";
    // $dbname = "ulusoft_be_fma_prestaties";

    $dbserver = "db";
    $dbuser = "ulusoft";
    $dbpassword = "ulusoft123456789";
    $dbname = "fma_prestaties";

    // $dbserver = "localhost";
    // $dbuser = "root";
    // $dbpassword = "";
    // $dbname = "fma_rapportage";

    $mysqli = new mysqli($dbserver, $dbuser, $dbpassword, $dbname);

    // Check connection
    if ($mysqli->connect_errno) 
    {    
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

?>