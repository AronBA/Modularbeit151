<?php
include "DB_config.php";

function connection(){
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }
    return $mysqli;
}