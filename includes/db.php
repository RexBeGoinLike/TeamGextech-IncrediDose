<?php
    $dbserver = "mysql-db";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "mydatabase";
    $port = 3306;


    $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname, $port);
?>