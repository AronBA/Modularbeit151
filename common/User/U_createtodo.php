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
<div class="well text-center"><h1>Create a new Todo</h1></div>
<div class="container">
    <form action="../Backend/DB/POST_createtodo.php" method="post">
        <!-- vorname -->
        <div class="form-group">
            <label for="firstname">titel *</label>
            <input type="text" name="titel" class="form-control" id="titel"

                   placeholder="Geben Sie Ihren Vornamen an."
                   required="true">
        </div>
        <!-- nachname -->
        <div class="form-group">
            <label for="content">text *</label>
            <textarea class='form-control' rows='5' name='content' id='content' maxlength='2000' placeholder="enter your description here"></textarea>
        </div>

        <!-- benutzername -->
        <div class="form-group">
            <label for="date"> date *</label>
            <input type="date" name="date" class="form-control" id="date" required>
        </div>

        <!-- password -->
        <div class="form-group">
            <label for="firstname">progress *</label>
            <input type="number" name="progress" class="form-control" id="progress"
                   min="0"
                   max="100"

                   placeholder="progress"
                   required="true">
        </div>

        <div class="form-group">
            <label for="firstname">priority *</label>
            <input type="number" name="priority" class="form-control" id="priority"
                   min="0"
                   max="5"
                   placeholder="priority"
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
