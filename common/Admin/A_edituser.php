<?php
require_once "../Backend/B_session.php";
require_once "../Backend/DB/POST_edituser.php";
require_once "../Backend/DB/DB_functions.php";


$mysqli = connection();

if (isset($_GET["userid"])){

    $id = $_GET["userid"];
    $query = 'select * from user where id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows) {
        $row = $result->fetch_assoc();
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $username = $row["username"];
    }
    mysqli_close($mysqli);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>To Do</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Datepicker -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css">
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap-datepicker.de.min.js"></script>



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
            <a class="navbar-brand" href="A_home.php">Just Do nothing</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="A_createuser.php">New User</a></li>
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
<div class="well text-center"><h1>You are editing <?=$username?></h1></div>
<div class="container">
    <form action="../Backend/DB/POST_edituser.php" method="post">
        <!-- vorname -->
        <div class="form-group">
            <label for="firstname">firstname *</label>
            <input type="text" name="firstname" class="form-control" id="firstname"
                   value="<?=$firstname?>"
                   placeholder="Enter your first name."
                   required="true">
        </div>
        <!-- nachname -->
        <div class="form-group">
            <label for="lastname">lastname *</label>
            <input type="text" name="lastname" class="form-control" id="lastname"
                   value="<?=$lastname?>"
                   placeholder="Enter your last name."
                   maxlength="30"
                   required="true">
        </div>
        <!-- benutzername -->
        <div class="form-group">
            <label for="username">username *</label>
            <input type="text" name="username" class="form-control" id="username"
                   value="<?=$username?>"
                   placeholder="Upper- and lower case letters, min 6 characters."
                   maxlength="30" required="true"
                   pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}"
                   title="Upper- and lower case letters, min 6 characters.">
        </div>
        <!-- password -->
        <div class="form-group">
            <label for="password">password *</label>
            <input type="password" name="password" class="form-control" id="password"
                   placeholder="Upper and lower case letters, numbers, special characters, at least 8 characters"
                   pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
                   title="At least one uppercase and one lowercase letter, one number and one special character, at least 8 characters long."
                   required="true">
        </div>
        <div class="form-group">
            <label>Choose a categorie:</label>
            <div class="multi-selector">

                <div class="select-field">
                    <input type="text" name="" placeholder="Choose categories" id="" class="input-selector">
                    <span class="down-arrow">&blacktriangledown;</span>
                </div>
                <!---------List of checkboxes and options----------->
                <div class="list">
                    <?php
                    $categories = getcategroies();
                    for ($i = 0; $i < sizeof($categories);$i++) {
                        $category = $categories[$i];

                        echo "<label for='task4' class='task'>
                            <input type='checkbox' value='$category' id='$category' name='$category'>
                             $category
                              </label>";
                    }
                    ?>
                </div>
            </div>
        </div>


        <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
        <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
    </form>
</div>


<script src="../../js/scripts.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
