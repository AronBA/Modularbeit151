<?php
session_start();
//Datenbankverbindung
$host = 'localhost';
$database = 'db_m151_modularbeit';
$username = 'root';
$password = '';

// mit datenbank verbinden
$mysqli = new mysqli($host, $username, $password, $database);

// fehlermeldung, falls die Verbindung fehl schlägt.
if ($mysqli->connect_error) {
die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
}
$error = '';
$message = '';
// Formular wurde gesendet und Besucher ist noch nicht angemeldet.
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)){
// username
if(!empty(trim($_POST['username']))){
$username = trim($_POST['username']);

// prüfung benutzername
if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{4,}/", $username) || strlen($username) > 30){
$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
}
} else {
$error .= "Geben Sie bitte den Benutzername an.<br />";
}
// password
if(!empty(trim($_POST['pwd']))){
$password = trim($_POST['pwd']);

// passwort gültig?
if(!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
$error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
}
} else {
$error .= "Geben Sie bitte das Passwort an.<br />";
}

// kein fehler
if(empty($error)){
// query
$query = "SELECT username, firstname,lastname, password, admin from user where username = ?";
// query vorbereiten
$stmt = $mysqli->prepare($query);
if($stmt===false){
$error .= 'prepare() failed '. $mysqli->error . '<br />';
}
// parameter an query binden
if(!$stmt->bind_param("s", $username)){
$error .= 'bind_param() failed '. $mysqli->error . '<br />';
}
// query ausführen
if(!$stmt->execute()){
$error .= 'execute() failed '. $mysqli->error . '<br />';
}
// daten auslesen
$result = $stmt->get_result();
// benutzer vorhanden
if($result->num_rows){
// userdaten lesen
$row = $result->fetch_assoc();
// passwort prüfen
if(password_verify($password, $row["password"]))
$message .= "Sie sind nun eingeloggt";
$_SESSION["id"] = $row["id"];
$_SESSION['username'] = $row["username"];
$_SESSION['firstname'] = $row["firstname"];
$_SESSION["lastname"] = $row["lastname"];
$_SESSION['admin'] = $row["admin"];
if ($row["admin"]) {
    header('Location: common/adminspace/admin.php');
} else {
    header('Location: common/todo_list.php');
}



// benutzername oder passwort stimmen nicht,
} else {
$error .= "Benutzername oder Passwort sind falsch";
}
} else {
$error .= "Benutzername oder Passwort sind falsch";
}

}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>To Do</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="jumbotron text-center">
    <h1>Just Do nothing</h1>
    <p>a not very unique ToDo Webapp</p>
</div>
    <div class="container">
        <div class="row vertical-offset-100">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please sign in</h3>
                    </div>
                    <div class="panel-body">
                        <form accept-charset="UTF-8" role="form" action="" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input type="input" class="form-control" id="username" name="username" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
                                </div>
                                <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
// fehlermeldung oder nachricht ausgeben
if(!empty($message)){
    echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
} else if(!empty($error)){
    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
}
?>





</body>
</html>