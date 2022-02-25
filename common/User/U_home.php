<?php
require_once "../Backend/B_session.php";
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
            <a class="navbar-brand" href="U_home.php">Just Do nothing</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="U_createtodo.php">New ToDo</a></li>
                <form class="navbar-form navbar-left" method="post" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search" name="search">
                    </div>
                    <button type="submit" class="btn btn-default" name="searchsub">Submit</button>
                </form>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><?php echo "Hello ". $_SESSION["firstname"]." ". $_SESSION["lastname"]?></a></li>
                <li><a href="../Backend/B_logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="well text-center"><h1>ToDo</h1></div>
<div class="container">
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">priority</th>
        <th scope="col">name</th>
        <th scope="col">text</th>
        <th scope="col">datebegin</th>
        <th scope="col">time left</th>
        <th scope="col">progress</th>
        <th scope="col">categorie</th>
        <th scope="col">created by</th>

    </tr>
    </thead>
    <tbody>
    <?php
    require_once "../Backend/DB/DB_functions.php";

    if (isset($_POST["searchsub"])){
        displaysearchedtodo($_SESSION["username"],$_POST["search"]);
        exit();
    }
        displaytodo($_SESSION["username"]);
    ?>

    </tbody>
</table>
    <a role="button" href="U_createtodo.php" type="button" class="btn btn-primary btn-block btn-success">add new Todo</a>

</div>
<script src="../../js/scripts.js"></script>
</body>
</html>
