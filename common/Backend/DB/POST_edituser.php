<?php

require_once "DB_connection.php";
include "DB_functions.php";

//funktion zum updaten der categorien
function updatecategories($uid){
    $mysqli = connection();
    $query = "DELETE FROM user_has_categories WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$uid );
    $stmt->execute();

    $categories = getcategroies();;
    for ($i = 0; $i < sizeof($categories); $i++){
        if (isset($_POST[$categories[$i]])){
            $cid = getcategorieid($_POST[$categories[$i]]);
            $query = "Insert into user_has_categories (user_id, categories_id) VALUES (?,?)";
            $mysqli = connection();
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $uid,$cid );
            $stmt->execute();

        }
    }
}

$error = $message =  '';



// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if(isset($_POST['id']) && !empty(trim($_POST['id']))){
        $uid = $_POST["id"];
    }



    // vorname vorhanden, mindestens 1 Zeichen und maximal 30 Zeichen lang
    if(isset($_POST['firstname']) && !empty(trim($_POST['firstname'])) && strlen(trim($_POST['firstname'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $firstname = htmlspecialchars(trim($_POST['firstname']));
    } else {echo "please us a valid firstname";}

    // nachname vorhanden, mindestens 1 Zeichen und maximal 30 zeichen lang
    if(isset($_POST['lastname']) && !empty(trim($_POST['lastname'])) && strlen(trim($_POST['lastname'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $lastname = htmlspecialchars(trim($_POST['lastname']));
    } else {echo "please us a valid lastname";}
    }

    // benutzername vorhanden, mindestens 6 Zeichen und maximal 30 zeichen lang
    if(isset($_POST['username']) && !empty(trim($_POST['username'])) && strlen(trim($_POST['username'])) <= 30){
        $username = trim($_POST['username']);
        // entspricht der benutzername unseren vogaben (minimal 6 Zeichen, Gross- und Kleinbuchstaben)
        if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username)){
            echo "please us a valid username";
        }
    } else {echo "please us a valid username";}


    // passwort vorhanden, mindestens 8 Zeichen
    if(isset($_POST['password']) && !empty(trim($_POST['password']))){
        $password = trim($_POST['password']);
        $password = password_hash($password, PASSWORD_DEFAULT);

        //entspricht das passwort unseren vorgaben? (minimal 8 Zeichen, Zahlen, Buchstaben, keine Zeilenumbrüche, mindestens ein Gross- und ein Kleinbuchstabe)
        if(!preg_match("/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
            echo "please us a valid password";
        }
    } else {echo "please us a valid password";}

    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if(empty($error)){
        $mysqli = connection();
        //firstname, lastname, username, password, email
        $query = "UPDATE user SET firstname = ?, lastname = ?,username = ?,password = ?  WHERE id = ?";
        // query vorbereiten
        $stmt = $mysqli->prepare($query);
        if($stmt===false){
            $error .= 'prepare() failed '. $mysqli->error . '<br />';
        }
        // parameter an query binden
        if(!$stmt->bind_param('ssssi', $firstname, $lastname, $username, $password, $uid)){
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

            updatecategories($uid);

            // weiterleiten auf login formular
            header('Location: ../../Admin/A_home.php');

        }

}

?>