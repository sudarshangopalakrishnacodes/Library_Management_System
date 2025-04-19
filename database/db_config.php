<?php


function connect()
{
    $host = "localhost";
    $dbname = "library_system";
    $username = "root";
    $password = "";

    try {
        $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    } catch (Exception $e) {

        echo $e->getMessage();
        echo "Connection Failed";
    }
}
