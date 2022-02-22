<?php
require_once "../Backend/B_session.php";
require_once "../Backend/DB/POST_createuser.php";
require_once "../Backend/DB/DB_functions.php";


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
<div class="well text-center"><h1>Create a new To do</h1></div>
<div class="container">
    <form action="../Backend/DB/POST_createtodo.php" method="post">
        <!-- vorname -->
        <div class="form-group">
            <label for="firstname">firstname *</label>
            <input type="text" name="firstname" class="form-control" id="firstname"

                   placeholder="Geben Sie Ihren Vornamen an."
                   required="true">
        </div>
        <!-- nachname -->
        <div class="form-group">
            <label for="lastname">lastname *</label>
            <input type="text" name="lastname" class="form-control" id="lastname"

                   placeholder="Geben Sie Ihren Nachnamen an"
                   maxlength="30"
                   required="true">
        </div>
        <!-- benutzername -->
        <div class="form-group">
            <label for="username">Benutzername *</label>
            <input type="text" name="username" class="form-control" id="username"

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
