<?php

require_once "DB_connection.php";
require_once "DB_functions.php";

//funktion zum updaten der categorie
function updatecategories($tid){

    $categories = getcategroies();;
    for ($i = 0; $i < sizeof($categories); $i++){
        if (isset($_POST[$categories[$i]])){
            $cid = getcategorieid($_POST[$categories[$i]]);
            $query = "UPDATE todo SET categories_id = ? where id = ?";
            $mysqli = connection();
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ii", $cid,$tid );
            $stmt->execute();

        }
    }
}

$error = $message =  '';


// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST") {
    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (isset($_POST['id']) && !empty(trim($_POST['id']))) {
        $tid = $_POST["id"];
    }
        if (isset($_POST['titel']) && !empty(trim($_POST['titel'])) && strlen(trim($_POST['titel'])) < 45) {
            $title = htmlspecialchars(trim($_POST['titel']));

        } else {echo "please us a valid titel";}

        if (strlen(trim($_POST['content'])) < 2000) {
            $text = $_POST["content"];
        } else {echo "please us a valid content";}

        if (isset($_POST['priority']) && !empty(trim($_POST['priority']))) {
            $priority = $_POST['priority'];
        } else {echo "please us a valid priority";}

        if (isset($_POST['date']) && !empty(trim($_POST['date']))) {
            $datefinsh = $_POST['date'];
        } else {echo "please us a valid date";}

        if (isset($_POST['progress']) && !empty(trim($_POST['progress'])) && strlen(trim($_POST['progress'])) < 3 && trim($_POST['progress']) < 100 && preg_match("/[0-9]/", trim($_POST['progress']))) {
            $progress = $_POST["progress"];
        } else {echo "please us a valid progress";}

        // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
        if (empty($error)) {
            $mysqli = connection();
            //firstname, lastname, username, password, email
            $query = "UPDATE todo SET name = ?, text = ?,progress = ?,priorety = ?,datefinish = ? WHERE id = ?";
            // query vorbereiten
            $stmt = $mysqli->prepare($query);
            if ($stmt === false) {
                $error .= 'prepare() failed ' . $mysqli->error . '<br />';
            }
            // parameter an query binden
            if (!$stmt->bind_param('sssssi', $title, $text, $progress, $priority, $datefinsh, $tid)) {
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

                updatecategories($tid);
                // weiterleiten auf login formular
                header('Location: ../../User/U_home.php');

            }

        }

}
?>