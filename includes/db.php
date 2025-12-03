<?php
    $dbserver = "mysql-db";
    $dbuser = "root";
    $dbpass = "123456";
    $dbname = "mydatabase";
    $port = 3306;


    $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname, $port);
?>