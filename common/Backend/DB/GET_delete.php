<?php
require_once "DB_connection.php";
$mysqli = connection();


//löscht den user und rederected den user wieder
if (isset($_GET["deleteuser"])){
    $id = $_GET["deleteuser"];
    $query = 'DELETE FROM user WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    header("location: ../../Admin/A_home.php");
    mysqli_close($mysqli);

}

//löscht das to do und rederected den user wieder
if (isset($_GET["deletetodo"])){
    $id = $_GET["deletetodo"];
    $query = 'DELETE FROM todo WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    header("location: ../../User/U_home.php");
    mysqli_close($mysqli);

}
//archiviert das todo
if (isset($_GET["archivetodo"])){
    $id = $_GET["archivetodo"];
    $query = 'UPDATE todo SET archive = 1 where id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    header("location: ../../User/U_home.php");
    mysqli_close($mysqli);

}
//löscht die kategorie
if (isset($_GET["deletecategorie"])){
    $id = $_GET["deletecategorie"];
    $query = 'DELETE FROM categories WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    header("location: ../../Admin/A_categories.php");
    mysqli_close($mysqli);

}


