<?php

require_once "DB_connection.php";
$error = $message =  '';
$firstname = $lastname = $admin = $username = '';

// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // vorname vorhanden, mindestens 1 Zeichen und maximal 30 Zeichen lang
    if(isset($_POST['firstname']) && !empty(trim($_POST['firstname'])) && strlen(trim($_POST['firstname'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $firstname = htmlspecialchars(trim($_POST['firstname']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
    }

    // select
    if(isset($_POST['categorie'])){
        $categroie = htmlspecialchars(trim($_POST['categorie']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Select an.<br />";
    }

    // nachname vorhanden, mindestens 1 Zeichen und maximal 30 zeichen lang
    if(isset($_POST['lastname']) && !empty(trim($_POST['lastname'])) && strlen(trim($_POST['lastname'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $lastname = htmlspecialchars(trim($_POST['lastname']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }

    // benutzername vorhanden, mindestens 6 Zeichen und maximal 30 zeichen lang
    if(isset($_POST['username']) && !empty(trim($_POST['username'])) && strlen(trim($_POST['username'])) <= 30){
        $username = trim($_POST['username']);
        // entspricht der benutzername unseren vogaben (minimal 6 Zeichen, Gross- und Kleinbuchstaben)
        if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username)){
            $error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Benutzernamen ein.<br />";
    }


    // passwort vorhanden, mindestens 8 Zeichen
    if(isset($_POST['password']) && !empty(trim($_POST['password']))){
        $password = trim($_POST['password']);
        $password = password_hash($password, PASSWORD_ARGON2I);

        //entspricht das passwort unseren vorgaben? (minimal 8 Zeichen, Zahlen, Buchstaben, keine Zeilenumbrüche, mindestens ein Gross- und ein Kleinbuchstabe)
        if(!preg_match("/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
            $error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }

    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if(empty($error)){
        //firstname, lastname, username, password, email
        $query = "Insert into user (firstname, lastname, username, password, admin, categories) values (?,?,?,?,?,?)";
        // query vorbereiten
        $stmt = $mysqli->prepare($query);
        if($stmt===false){
            $error .= 'prepare() failed '. $mysqli->error . '<br />';
        }
        // parameter an query binden
        if(!$stmt->bind_param('ssssss', $firstname, $lastname, $username, $password, $admin, $categroie)){
            $error .= 'bind_param() failed '. $mysqli->error . '<br />';
        }

        // query ausführen
        if(!$stmt->execute()){
            $error .= 'execute() failed '. $mysqli->error . '<br />';
        }
        // kein Fehler!
        if(empty($error)){
            $message .= "Die Daten wurden erfolgreich in die Datenbank geschrieben<br/ >";
            // felder leeren > oder weiterleitung auf anderes script: z.B. Login!
            $username = $password = $firstname = $lastname = $admin =  '';
            // verbindung schliessen
            $mysqli->close();

            // weiterleiten auf login formular
            header('Location: ../../Admin/A_home.php');
        }

    }
}

?>