<?php

require_once "DB_connection.php";
include "DB_functions.php";
include "../B_session.php";

function categories(){
    $categories = getcategroies();;
    for ($i = 0; $i < sizeof($categories); $i++) {
        if (isset($_POST[$categories[$i]])){
            $cid = getcategorieid($_POST[$categories[$i]]);
            return $cid;

        }
    }
}


$error = $message =  '';
$firstname = $lastname = $admin = $username = '';

// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $user = $_SESSION["username"];
    $uid = getuserid($user);

    $curentdate = date("Y-m-d");

    if (isset($_POST['titel'])) {
        $todoError['error'] = "";
        if (isset($_POST['titel']) && !empty(trim($_POST['titel'])) && strlen(trim($_POST['titel'])) < 45) {
            $titel = htmlspecialchars(trim($_POST['titel']));

        }

        if (strlen(trim($_POST['content'])) < 2000) {
            $content = $_POST["content"];
        }

        if (isset($_POST['priority']) && !empty(trim($_POST['priority']))) {
            $priority = $_POST['priority'];
        }

        if (isset($_POST['date']) && !empty(trim($_POST['date']))) {
            $date = $_POST['date'];
        }

        if (isset($_POST['progress']) && !empty(trim($_POST['progress'])) && strlen(trim($_POST['progress'])) < 3 && trim($_POST['progress']) < 100 && preg_match("/[0-9]/", trim($_POST['progress']))) {
            $progess = $_POST["progress"];
        }

        $mysqli = connection();
        //firstname, lastname, username, password, email
        $query = "Insert into todo (name, text, datebegin, datefinish, priorety,progress,categories_id,user_id) values (?,?,?,?,?,?,?,?)";
        // query vorbereiten
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            $error .= 'prepare() failed ' . $mysqli->error . '<br />';
        }
        // parameter an query binden
        if (!$stmt->bind_param('ssssssss', $titel, $content, $curentdate, $date, $priority, $progess,categories(),$uid)) {
            $error .= 'bind_param() failed ' . $mysqli->error . '<br />';
        }

        // query ausführen
        if (!$stmt->execute()) {
            $error .= 'execute() failed ' . $mysqli->error . '<br />';
        }
        // kein Fehler!
        if (empty($error)) {
            $message .= "Die Daten wurden erfolgreich in die Datenbank geschrieben<br/ >";
            // verbindung schliessen
            $mysqli->close();



            // weiterleiten auf login formular
            header('Location: ../../User/U_home.php');


        }
    }
}
?>