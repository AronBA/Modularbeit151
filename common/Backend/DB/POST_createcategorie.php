<?php

require_once "DB_connection.php";
require_once "DB_functions.php";



$error = $message =  '';
$firstname = $lastname = $admin = $username = '';

// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // vorname vorhanden, mindestens 1 Zeichen und maximal 30 Zeichen lang
    if(isset($_POST['name']) && !empty(trim($_POST['name'])) && strlen(trim($_POST['name'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $name = htmlspecialchars(trim($_POST['name']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= 'Please use a valid name.<br />';
    }


    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if(empty($error)){



        $mysqli = connection();

        $query = "Insert into categories (name) values (?)";
        // query vorbereiten
        $stmt = $mysqli->prepare($query);
        if($stmt===false){
            $error .= 'prepare() failed '. $mysqli->error . '<br />';
        }
        // parameter an query binden
        if(!$stmt->bind_param('s', $name)){
            $error .= 'bind_param() failed '. $mysqli->error . '<br />';
        }

        // query ausführen
        if(!$stmt->execute()){
            $error .= 'execute() failed '. $mysqli->error . '<br />';
        }
        // kein Fehler!
        if(empty($error)){
            $message .= "Die Daten wurden erfolgreich in die Datenbank geschrieben<br/ >";
            // verbindung schliessen
            $mysqli->close();

            // weiterleiten auf login formular
            header('Location: ../../Admin/A_categories.php');

        }

    }
}

?>