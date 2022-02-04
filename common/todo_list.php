<?php
session_start();
if (!isset($_SESSION["username"])){
    header("Location: error.php");
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
            <a class="navbar-brand" href="#">Just Do nothing</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">To Do <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION["firstname"]." ". $_SESSION["lastname"]?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><?php echo $_SESSION["username"]; ?></a></li>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings</a></li>

                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Categorie</th>
        <th scope="col">Beginn</th>
        <th scope="col">End</th>
        <th scope="col">Time left</th>
        <th scope="col">Progress</th>
        <th scope="col">Priorety</th>
        <th scope="col">edit</th>
        <th scope="col">archive</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $host = 'localhost';
    $database = 'db_m151_modularbeit';
    $username = 'root';
    $password = '';
    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }
    $id = $_SESSION["id"];
    $query = 'SELECT * from todo';
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {



            $ido = $row["id"];
            $title = $row["name"];
            $categorie = $row["categorie"];
            $start = $row["datebegin"];
            $end = $row["datefinish"];
            $progress = $row["progress"];
            $priorety = $row["priorety"];


            echo "
        <tr>
        <th scope='row'>$ido</th>
        <td>$title</td>
        <td>$categorie</td>
        <td>$start</td>
        <td>$end</td>
        <td>100 days</td>
        <td>$progress</td>
        <td>$priorety</td>
        </tr>";}

    } else {
        echo "0 results";
    }
    $mysqli->close();
    ?>

    </tbody>
</table>
</body>
</html>
