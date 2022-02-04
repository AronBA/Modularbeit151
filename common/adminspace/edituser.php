<?php
session_start();
if (!isset($_SESSION["username"])){
    header("Location: ../error.php");
}

if(isset($_GET['edituser'])) {
    $id = $_GET["edituser"];
}

//verbindung zur Datenbank Auslagern
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

// Initialisierung
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
        $query = "UPDATE user SET firstname = ?,lastname = ?,username = ?, password = ?, categories = ? WHERE id = ?";
        // query vorbereiten
        $stmt = $mysqli->prepare($query);
        if($stmt===false){
            $error .= 'prepare() failed '. $mysqli->error . '<br />';
        }
        // parameter an query binden
        if(!$stmt->bind_param('ssssss', $firstname, $lastname, $username, $password, $categroie,$id)){
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
            header('Location: admin.php');
        }

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
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="admin.php">Just Do nothing</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="createuser.php">New User</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Create Categorie</a></li>
                        <li><a href="#">Edit Categorie</a></li>
                        <li><a href="#">Delete Categorie</a></li>

                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION["firstname"]." ". $_SESSION["lastname"]?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><?php echo $_SESSION["username"]; ?></a></li>
                        <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
                        <li><a href="../logout.php"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings</a></li>

                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="well text-center"><h1>Edit the User</h1></div>
<div class="container">
<?php
// Ausgabe der Fehlermeldungen
if(!empty($error)){
    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
} else if (!empty($message)){
    echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
}
?>
<form action="" method="post">
    <!-- vorname -->
    <div class="form-group">
        <label for="firstname">firstname *</label>
        <input type="text" name="firstname" class="form-control" id="firstname"
               value="<?php echo $firstname ?>"
               placeholder="Geben Sie Ihren Vornamen an."
               required="true">
    </div>
    <!-- nachname -->
    <div class="form-group">
        <label for="lastname">lastname *</label>
        <input type="text" name="lastname" class="form-control" id="lastname"
               value="<?php echo $lastname ?>"
               placeholder="Geben Sie Ihren Nachnamen an"
               maxlength="30"
               required="true">
    </div>
    <!-- benutzername -->
    <div class="form-group">
        <label for="username">Benutzername *</label>
        <input type="text" name="username" class="form-control" id="username"
               value="<?php echo $username ?>"
               placeholder="Gross- und Keinbuchstaben, min 6 Zeichen."
               maxlength="30" required="true"
               pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}"
               title="Gross- und Keinbuchstaben, min 6 Zeichen.">
    </div>
    <!-- password -->
    <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" name="password" class="form-control" id="password"
               placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute"
               pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
               title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute."
               required="true">
    </div>
    <div class="form-group">
        <label for="cars">Choose a categorie:</label>
        <select name='categorie'>
            <?php
            include "../backend.php";

            foreach (getcategroies() as $categroie){
                $categroie = $categroie["name"];
                echo "<option value='$categroie'>$categroie</option>";
            }
            ?>
        </select>
    </div>


    <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
    <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
</form>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
