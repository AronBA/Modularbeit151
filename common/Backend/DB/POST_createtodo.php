<?php



include "../B_session.php";
require_once "DB_connection.php";
include "DB_functions.php";

#
function updatecategories()
{
    $categories = getcategroies();;
    for ($i = 0; $i < sizeof($categories); $i++) {
        if (isset($_POST[$categories[$i]])) {
            $cid = getcategorieid($_POST[$categories[$i]]);
            return $cid;

        }
    }
}




$error = $message = '';
$firstname = $lastname = $admin = $username = '';

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $currentDate = date("Y-m-d");
    $user = $_SESSION["username"];
    $uid = getuserid($user);

    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // vorname vorhanden, mindestens 1 Zeichen und maximal 30 Zeichen lang
    if (isset($_POST['title']) && !empty(trim($_POST['title'])) && strlen(trim($_POST['title'])) <= 30) {
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $title = htmlspecialchars(trim($_POST['title']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
    }

    // nachname vorhanden, mindestens 1 Zeichen und maximal 30 zeichen lang
    if (isset($_POST['editor']) && !empty(trim($_POST['editor'])) && strlen(trim($_POST['editor'])) <= 300) {
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $text = htmlspecialchars(trim($_POST['editor']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }


    if (isset($_POST['priority']) && !empty(trim($_POST['priority'])) && strlen(trim($_POST['priority'])) <= 30) {
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $priority = htmlspecialchars(trim($_POST['priority']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }

    if (isset($_POST['progress']) && !empty($_POST['progress'])) {
        $progress = $_POST['progress'];


    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }




    // passwort vorhanden, mindestens 8 Zeichen
    if (isset($_POST['date']) && !empty(trim($_POST['date']))) {
        $datefinish = trim($_POST['date']);


    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }

    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if (empty($error)) {


        $mysqli = connection();
        //firstname, lastname, username, password, email
        $query = "Insert into todo (name, text, datebegin, datefinish,priorety,progress, categories_id, user_id) values (?,?,?,?,?,?,?)";
        // query vorbereiten
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            $error .= 'prepare() failed ' . $mysqli->error . '<br />';
        }
        // parameter an query binden
        if (!$stmt->bind_param('sssssss', $title, $text, $currentDate, $datefinish, $priority,$progress,updatecategories(),$uid)) {
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

