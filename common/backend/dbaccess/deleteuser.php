<?php
require_once "dbconnection.php";


if (isset($_GET["deleteuser"])){
    $id = $_GET["deleteuser"];
    $query = 'DELETE FROM db_m151_modularbeit.user WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    header("location: ../../adminspace/admin.php");
}

