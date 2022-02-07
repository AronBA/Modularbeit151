<?php
require_once "../B_session.php";
require_once "DB_connection.php";
$mysqli = connection();
$error = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)){

    if(!empty(trim($_POST['username']))){
        $username = trim($_POST['username']);


        if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{4,}/", $username) || strlen($username) > 30){
            $error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";}
    } else {
        $error .= "Geben Sie bitte den Benutzername an.<br />";
    }

    if(!empty(trim($_POST['pwd']))){
        $password = trim($_POST['pwd']);
        if(!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
            $error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        $error .= "Geben Sie bitte das Passwort an.<br />";
    }

    if(empty($error)){
        $query = "SELECT username, firstname,lastname, password, admin from user where username = ?";
        $stmt = $mysqli->prepare($query);

        if($stmt===false){
            $error .= 'prepare() failed '. $mysqli->error . '<br />';
        }

        if(!$stmt->bind_param("s", $username)){
            $error .= 'bind_param() failed '. $mysqli->error . '<br />';
        }

        if(!$stmt->execute()){
            $error .= 'execute() failed '. $mysqli->error . '<br />';
        }

        $result = $stmt->get_result();

        if($result->num_rows){
            $row = $result->fetch_assoc();

            if(password_verify($password, $row["password"]))

                $message .= "Sie sind nun eingeloggt";
                $_SESSION['login'] = true;
                $_SESSION['username'] = $row["username"];
                $_SESSION['firstname'] = $row["firstname"];
                $_SESSION["lastname"] = $row["lastname"];
                $_SESSION['admin'] = $row["admin"];
                if ($row["admin"]) {
                    header('Location: ../../Admin/A_home.php');

                } else {
                    header('Location: ../../User/U_home.php');
                }
        } else {
            $error .= "Benutzername oder Passwort sind falsch";
        }
    } else {
        $error .= "Benutzername oder Passwort sind falsch";
    }
}

