<?php
require_once "DB_connection.php";
$mysqli = connection();

if (isset($_GET["deleteuser"])){
    $id = $_GET["deleteuser"];
    $query = 'DELETE FROM user WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    header("location: ../../Admin/A_home.php");
    mysqli_close($mysqli);

}

