<?php
$categories = getcategroies();

function getcategroies(){
    $host = 'localhost';
    $database = 'db_m151_modularbeit';
    $username = 'root';
    $password = '';
    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }

    $query = 'SELECT name from categories';
    $result = $mysqli->query($query);

    return $result;
}







