<?php
require_once "DB_config.php";
//function welche eine mysqli connection zurück gibt
function connection(){
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }
    return $mysqli;
}